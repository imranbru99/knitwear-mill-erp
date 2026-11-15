<?php

namespace Tests\Feature;

use App\Models\Bom;
use App\Models\BomLine;
use App\Models\Buyer;
use App\Models\BuyerOrder;
use App\Models\ChartOfAccount;
use App\Models\CostingSheet;
use App\Models\Grn;
use App\Models\GrnLine;
use App\Models\Issue;
use App\Models\IssueType;
use App\Models\Machine;
use App\Models\PostingRule;
use App\Models\ProductionOrder;
use App\Models\Style;
use App\Models\Supplier;
use App\Models\TnaTemplate;
use App\Models\TnaTemplateTask;
use App\Models\YarnInspection;
use App\Services\Accounts\PostingService;
use App\Services\Documents\NumberingService;
use App\Services\Hr\PayrollService;
use App\Services\Inventory\StockService;
use App\Services\Issues\IssueEngine;
use App\Services\Merchandising\CostingService;
use App\Services\Merchandising\MrpService;
use App\Services\Merchandising\TnaService;
use App\Services\Production\KnittingMetrics;
use App\Services\Quality\AqlService;
use App\Services\Workflow\DocumentWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Tests\TestCase;

class FactoryEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \ImranDevBd\AiHub\Facades\AIHub::fake('Check the shade card and hold the lot.');
        foreach ([
            'manual', 'yarn_short', 'shade_mismatch', 'inspection_fail', 'bom_missing', 'tna_late',
        ] as $code) {
            IssueType::query()->create([
                'code' => $code,
                'module' => 'orders',
                'name' => $code,
                'default_owner_role' => 'Merchandiser',
                'sla_minutes' => 60,
                'severity' => 'major',
                'is_active' => true,
            ]);
        }
    }

    public function test_document_numbers_follow_the_july_financial_year(): void
    {
        $number = app(NumberingService::class)->next('purchase_order');
        $this->assertMatchesRegularExpression('/^YPO-\d{4}-00001$/', $number);
        $this->assertSame($number, preg_replace('/00001$/', '00001', $number));
        $second = app(NumberingService::class)->next('purchase_order');
        $this->assertStringEndsWith('00002', $second);
    }

    public function test_stock_ledger_refuses_a_negative_issue(): void
    {
        $stock = app(StockService::class);
        $stock->move([
            'item_type' => 'yarn', 'item_code' => '2/32 oatmeal', 'lot_no' => 'L1',
            'qty_in' => 10, 'uom' => 'kg',
        ]);
        $this->assertEquals(10.0, $stock->onHand('yarn', '2/32 oatmeal'));
        $this->expectException(RuntimeException::class);
        $stock->move([
            'item_type' => 'yarn', 'item_code' => '2/32 oatmeal', 'lot_no' => 'L1',
            'qty_out' => 12, 'uom' => 'kg',
        ]);
    }

    public function test_mrp_explodes_sweater_yarn_with_wastage(): void
    {
        $style = Style::query()->create([
            'code' => 'SW-1', 'name' => 'Crew', 'slug' => 'crew', 'product_type' => 'pullover',
            'construction' => 'fully-fashioned', 'production_route' => 'sweater', 'status' => 'active',
        ]);
        $buyer = Buyer::query()->create(['code' => 'B1', 'name' => 'Northwind', 'status' => 'active']);
        $bom = Bom::query()->create(['style_id' => $style->id, 'version' => 1, 'status' => 'approved']);
        BomLine::query()->create([
            'bom_id' => $bom->id, 'item_type' => 'yarn', 'description' => '2/32 oatmeal',
            'uom' => 'kg', 'qty_per_piece' => 0.2, 'wastage_percent' => 5,
        ]);
        $order = BuyerOrder::query()->create([
            'number' => 'BPO-1', 'buyer_id' => $buyer->id, 'style_id' => $style->id, 'buyer_po_no' => 'PO1',
            'quantity' => 100, 'ex_factory_date' => '2026-12-01', 'ship_date' => '2026-12-05',
            'production_route' => 'sweater', 'status' => 'confirmed',
        ]);
        $job = ProductionOrder::query()->create([
            'number' => 'JOB-1', 'buyer_order_id' => $order->id, 'route' => 'sweater', 'quantity' => 100, 'status' => 'planned',
        ]);

        $rows = app(MrpService::class)->explode($job);

        $this->assertEquals(21.0, (float) $rows->first()->required_qty);
        $this->assertEquals(21.0, (float) $rows->first()->shortfall_qty);
        $this->assertTrue(Issue::query()->whereHas('issueType', fn ($q) => $q->where('code', 'yarn_short'))->exists());
    }

    public function test_costing_quotes_profit_on_top_of_mill_cost(): void
    {
        $style = Style::query()->create([
            'code' => 'SW-2', 'name' => 'Cardigan', 'slug' => 'cardigan', 'product_type' => 'cardigan',
            'construction' => 'flat', 'production_route' => 'sweater', 'status' => 'active',
        ]);
        $sheet = CostingSheet::query()->create([
            'number' => 'CST-1', 'style_id' => $style->id, 'yarn_cost' => 2, 'knitting_cost' => 1,
            'profit_percent' => 10, 'status' => 'draft',
        ]);
        $priced = app(CostingService::class)->calculate($sheet);
        $this->assertEquals(3.0, (float) $priced->total_cost);
        $this->assertEquals(3.3, (float) $priced->quoted_price);
        $this->assertEquals(4.326, app(CostingService::class)->yarnCostPerPiece(400, 10, 5, 3));
    }

    public function test_tna_walks_back_from_the_ship_date_and_skips_friday(): void
    {
        $buyer = Buyer::query()->create(['code' => 'B2', 'name' => 'River', 'status' => 'active']);
        $style = Style::query()->create([
            'code' => 'PL-1', 'name' => 'Polo', 'slug' => 'polo', 'product_type' => 'polo',
            'construction' => 'cut-and-sew', 'production_route' => 'cut_and_sew', 'status' => 'active',
        ]);
        $template = TnaTemplate::query()->create(['name' => 'Polo', 'route' => 'cut_and_sew', 'is_active' => true]);
        TnaTemplateTask::query()->create(['tna_template_id' => $template->id, 'name' => 'Ex-factory', 'department' => 'Shipping', 'offset_days' => 0, 'sort' => 2]);
        TnaTemplateTask::query()->create(['tna_template_id' => $template->id, 'name' => 'Cutting', 'department' => 'Cutting', 'offset_days' => 1, 'sort' => 1]);
        $order = BuyerOrder::query()->create([
            'number' => 'BPO-2', 'buyer_id' => $buyer->id, 'style_id' => $style->id, 'buyer_po_no' => 'PO2',
            'quantity' => 500, 'ex_factory_date' => '2026-11-18', 'ship_date' => '2026-11-20',
            'production_route' => 'cut_and_sew', 'status' => 'confirmed',
        ]);
        $job = ProductionOrder::query()->create([
            'number' => 'JOB-2', 'buyer_order_id' => $order->id, 'route' => 'cut_and_sew', 'quantity' => 500, 'status' => 'planned',
        ]);
        app(TnaService::class)->generate($job);
        $ex = $job->tnaTasks()->where('name', 'Ex-factory')->first();
        $this->assertSame('2026-11-20', $ex->planned_end->toDateString());
        $this->assertSame('2026-11-19', $job->tnaTasks()->where('name', 'Cutting')->first()->planned_end->toDateString());
        $this->assertFalse(app(TnaService::class)->isWorkingDay(\Carbon\Carbon::parse('2026-11-20')));
    }

    public function test_aql_fails_a_lot_that_exceeds_major_accept(): void
    {
        $aql = app(AqlService::class);
        $this->assertSame(200, $aql->sampleSize(5000));
        $this->assertSame(10, $aql->acceptNumber(200, 2.5));
        $pass = $aql->judge(5000, 2.5, 4.0, 10, 14, 0);
        $fail = $aql->judge(5000, 2.5, 4.0, 11, 0, 0);
        $this->assertSame('pass', $pass['result']);
        $this->assertSame('fail', $fail['result']);
    }

    public function test_payroll_pays_double_time_and_piece_rate(): void
    {
        $gross = app(PayrollService::class)->gross(30000, 26, 2, 100, 5);
        $this->assertEquals(27000.0, $gross);
    }

    public function test_posting_is_idempotent(): void
    {
        $debit = ChartOfAccount::query()->create(['code' => '1400', 'name' => 'Yarn', 'type' => 'asset']);
        $credit = ChartOfAccount::query()->create(['code' => '2100', 'name' => 'GRNI', 'type' => 'liability']);
        PostingRule::query()->create(['event' => 'grn', 'debit_account_id' => $debit->id, 'credit_account_id' => $credit->id, 'active' => true]);
        $service = app(PostingService::class);
        $first = $service->post('grn', 1500, 'grn:1');
        $second = $service->post('grn', 1500, 'grn:1');
        $this->assertSame($first->id, $second->id);
        $this->assertEquals(1500.0, (float) $first->voucherLines()->sum('debit'));
    }

    public function test_issue_sla_escalates_and_closure_needs_capa(): void
    {
        $engine = app(IssueEngine::class);
        $issue = $engine->raise(['type_code' => 'manual', 'title' => 'Needle line on front', 'idempotent' => false]);
        $issue->update(['sla_due_at' => now()->subHour(), 'escalation_level' => 0]);
        $this->assertSame(1, $engine->escalateDue());
        $this->assertSame(1, $issue->fresh()->escalation_level);
        $this->expectException(ValidationException::class);
        $engine->close($issue->fresh(), []);
    }

    public function test_repeat_defect_on_the_same_machine_becomes_chronic(): void
    {
        $machine = Machine::query()->create(['code' => 'M1', 'name' => 'Flat 12', 'kind' => 'flat', 'status' => 'running']);
        $engine = app(IssueEngine::class);
        foreach (range(1, 3) as $n) {
            $engine->raise([
                'type_code' => 'manual',
                'title' => 'Hole '.$n,
                'machine_id' => $machine->id,
                'idempotent' => false,
            ]);
        }
        $this->assertTrue(Issue::query()->where('chronic', true)->exists());
    }

    public function test_rejected_yarn_does_not_enter_stock_and_sweater_confirm_builds_tna(): void
    {
        $supplier = Supplier::query()->create(['code' => 'S1', 'name' => 'Meghna', 'type' => 'yarn_spinner', 'status' => 'active']);
        $grn = Grn::query()->create([
            'number' => 'GRN-1', 'supplier_id' => $supplier->id, 'challan_no' => 'CH-9', 'received_on' => '2026-11-16', 'status' => 'draft',
        ]);
        $line = GrnLine::query()->create([
            'grn_id' => $grn->id, 'lot_no' => 'DYE-44', 'item_code' => '2/32 oatmeal', 'qty' => 25, 'uom' => 'kg', 'net_weight' => 24.6,
        ]);
        YarnInspection::query()->create([
            'grn_line_id' => $line->id, 'shade_ok' => false, 'decision' => 'reject',
        ]);
        app(DocumentWorkflow::class)->post($grn);
        $this->assertEquals(0.0, app(StockService::class)->onHand('yarn', '2/32 oatmeal'));
        $this->assertTrue(Issue::query()->whereHas('issueType', fn ($q) => $q->where('code', 'shade_mismatch'))->exists());

        $buyer = Buyer::query()->create(['code' => 'B3', 'name' => 'North', 'status' => 'active']);
        $style = Style::query()->create([
            'code' => 'SW-9', 'name' => 'Crew', 'slug' => 'crew-9', 'product_type' => 'pullover',
            'construction' => 'fully-fashioned', 'production_route' => 'sweater', 'status' => 'active',
        ]);
        $template = TnaTemplate::query()->create(['name' => 'Sweater', 'route' => 'sweater', 'is_active' => true]);
        TnaTemplateTask::query()->create([
            'tna_template_id' => $template->id, 'name' => 'Knitting start', 'department' => 'Knitting', 'offset_days' => 5, 'sort' => 1,
        ]);
        $po = BuyerOrder::query()->create([
            'number' => 'BPO-DRAFT', 'buyer_id' => $buyer->id, 'style_id' => $style->id, 'buyer_po_no' => 'PO9',
            'quantity' => 300, 'ex_factory_date' => '2027-02-01', 'ship_date' => '2027-02-05',
            'production_route' => 'sweater', 'status' => 'draft',
        ]);
        $job = app(DocumentWorkflow::class)->confirm($po);
        $this->assertSame('confirmed', $po->fresh()->status);
        $this->assertStringStartsWith('BPO-', $po->fresh()->number);
        $this->assertSame('sweater', $job->route);
        $this->assertNotNull($job->tnaTasks()->where('name', 'Knitting start')->first());
        $this->assertEquals(77.92, app(KnittingMetrics::class)->efficiency(10, 12, 154));
    }

    public function test_public_home_and_inquiry_are_reachable(): void
    {
        $this->get('/')->assertOk();
        $this->post('/inquiries', [
            'contact_name' => 'Ada Buyer',
            'email' => 'ada@northwind.test',
            'style_hint' => '12gg oatmeal crew',
            'quantity' => 800,
            'currency' => 'USD',
        ])->assertRedirect();
        $this->assertDatabaseHas('inquiries', ['email' => 'ada@northwind.test', 'source' => 'website']);
    }
}
