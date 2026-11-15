<?php

namespace Database\Seeders;

use App\Models\AutomationRule;
use App\Models\Bom;
use App\Models\BomLine;
use App\Models\Branch;
use App\Models\Buyer;
use App\Models\BuyerOrder;
use App\Models\ChartOfAccount;
use App\Models\CmsPage;
use App\Models\Colour;
use App\Models\Defect;
use App\Models\DowntimeLog;
use App\Models\Grn;
use App\Models\GrnLine;
use App\Models\Inspection;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\SampleComment;
use App\Models\SampleOrder;
use App\Models\YarnInspection;
use App\Services\Issues\IssueEngine;
use App\Models\Department;
use App\Models\ExchangeRate;
use App\Models\Faq;
use App\Models\Holiday;
use App\Models\IssueType;
use App\Models\Machine;
use App\Models\Module;
use App\Models\PostingRule;
use App\Models\ProductCategory;
use App\Models\Size;
use App\Models\Style;
use App\Models\Supplier;
use App\Models\TnaTemplate;
use App\Models\TnaTemplateTask;
use App\Models\UnitConversion;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Models\YarnCount;
use App\Services\Workflow\DocumentWorkflow;
use App\Support\Integrations\Slugger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA synchronous = OFF');
            DB::statement('PRAGMA journal_mode = MEMORY');
        }

        DB::transaction(function (): void {
            $this->seedMill();
        });
    }

    private function seedMill(): void
    {
        $head = Branch::query()->firstOrCreate(['code' => 'HO'], ['name' => 'Head Office', 'name_bn' => 'প্রধান কার্যালয়', 'type' => 'head_office', 'address' => 'Fatullah, Narayanganj', 'is_active' => true]);
        Branch::query()->firstOrCreate(['code' => 'U1'], ['name' => 'Unit-1 Knitting', 'name_bn' => 'ইউনিট-১ বুনন', 'type' => 'knitting', 'is_active' => true]);
        Branch::query()->firstOrCreate(['code' => 'U2'], ['name' => 'Unit-2 Sewing', 'name_bn' => 'ইউনিট-২ সেলাই', 'type' => 'sewing', 'is_active' => true]);
        Branch::query()->firstOrCreate(['code' => 'WASH'], ['name' => 'Washing Unit', 'type' => 'washing', 'is_active' => true]);
        Branch::query()->firstOrCreate(['code' => 'WH'], ['name' => 'Yarn Warehouse', 'type' => 'warehouse', 'is_active' => true]);

        foreach ([
            ['MER', 'Merchandising', 'Merchandiser'],
            ['YARN', 'Yarn Store', 'Store In-charge'],
            ['KNT', 'Knitting', 'Knitting Manager'],
            ['LNK', 'Linking', 'Linking Supervisor'],
            ['QC', 'Quality', 'Quality Manager'],
            ['ACC', 'Accounts', 'Accounts Manager'],
            ['HR', 'Human Resources', 'HR Manager'],
            ['COM', 'Compliance', 'Compliance Officer'],
        ] as [$code, $name, $role]) {
            Department::query()->firstOrCreate(['code' => $code], ['name' => $name, 'head_role' => $role, 'is_active' => true]);
        }

        foreach ([
            'foundation', 'issues', 'crm', 'styles', 'sampling', 'costing', 'orders', 'bom', 'purchase',
            'yarn_store', 'knitting', 'linking', 'washing', 'cutting', 'quality', 'packing', 'commercial',
            'accounts', 'hr', 'compliance', 'maintenance', 'security', 'website', 'ai', 'webshell',
        ] as $key) {
            Module::query()->firstOrCreate(['key' => $key], ['name' => Str::headline($key), 'enabled' => $key !== 'webshell']);
        }

        $tables = require database_path('schema/knitwear.php');
        $byModule = [];
        foreach ($tables as $meta) {
            $perm = Str::snake(Str::pluralStudly($meta['model']));
            $view = Permission::findOrCreate($perm.'.view');
            $manage = Permission::findOrCreate($perm.'.manage');
            $byModule[$meta['module']][] = $view;
            $byModule[$meta['module']][] = $manage;
        }

        $roles = [
            'Super Admin', 'Managing Director', 'Director Operations', 'General Manager', 'Merchandising Manager',
            'Merchandiser', 'Sample In-charge', 'Pattern/CAD Master', 'Knit Programmer', 'Purchase Manager',
            'Store In-charge', 'Knitting Manager', 'Knitting Supervisor', 'Linking Supervisor', 'Mending Supervisor',
            'Washing/Dyeing Manager', 'Finishing In-charge', 'Cutting In-charge', 'Sewing Line Chief', 'Industrial Engineer',
            'Quality Manager', 'Line QC', 'Final Auditor', 'Packing In-charge', 'Shipping Manager', 'Commercial Manager',
            'Accounts Manager', 'Accountant', 'HR Manager', 'Compliance Officer', 'Maintenance Engineer', 'Security/Gate',
            'Buyer', 'Supplier', 'Auditor',
        ];
        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
        $all = Permission::all();
        Role::findByName('Super Admin')->syncPermissions($all);
        Role::findByName('Managing Director')->syncPermissions($all);
        $this->grant('Merchandiser', $byModule, ['crm', 'styles', 'sampling', 'costing', 'orders', 'bom']);
        $this->grant('Store In-charge', $byModule, ['purchase', 'yarn_store']);
        $this->grant('Knitting Manager', $byModule, ['knitting', 'linking', 'washing']);
        $this->grant('Quality Manager', $byModule, ['quality']);
        $this->grant('Accounts Manager', $byModule, ['accounts', 'commercial', 'packing']);
        $this->grant('HR Manager', $byModule, ['hr']);
        $this->grant('Compliance Officer', $byModule, ['compliance', 'maintenance', 'security']);
        $this->grant('Sewing Line Chief', $byModule, ['cutting']);

        $password = 'Factory#2026';
        $admin = User::query()->updateOrCreate(['email' => 'admin@knitwear.test'], [
            'name' => 'Mill Admin', 'password' => $password, 'branch_id' => $head->id,
            'sees_all_branches' => true, 'is_active' => true, 'locale' => 'en',
        ]);
        $admin->syncRoles(['Super Admin']);

        $this->user('merchandiser@knitwear.test', 'Nusrat Rahman', 'Merchandiser', $head->id, $password);
        $this->user('store@knitwear.test', 'Karim Uddin', 'Store In-charge', $head->id, $password);
        $this->user('knitting@knitwear.test', 'Shahidul Islam', 'Knitting Manager', $head->id, $password);
        $this->user('qc@knitwear.test', 'Lima Akter', 'Quality Manager', $head->id, $password);
        $this->user('accounts@knitwear.test', 'Farhana Begum', 'Accounts Manager', $head->id, $password);

        foreach ([
            ['NVY', 'Navy', 'নেভি'], ['OAT', 'Oatmeal', 'ওটমিল'], ['BLK', 'Black', 'কালো'], ['WHT', 'White', 'সাদা'],
        ] as [$code, $name, $bn]) {
            Colour::query()->firstOrCreate(['code' => $code], ['name' => $name, 'name_bn' => $bn]);
        }
        foreach (['XS', 'S', 'M', 'L', 'XL'] as $i => $size) {
            Size::query()->firstOrCreate(['code' => $size], ['name' => $size, 'sort' => $i, 'system' => 'alpha']);
        }
        UnitOfMeasure::query()->firstOrCreate(['code' => 'kg'], ['name' => 'Kilogram', 'name_bn' => 'কেজি']);
        UnitOfMeasure::query()->firstOrCreate(['code' => 'pcs'], ['name' => 'Piece', 'name_bn' => 'পিস']);
        UnitOfMeasure::query()->firstOrCreate(['code' => 'cone'], ['name' => 'Cone', 'name_bn' => 'কোন']);
        UnitConversion::query()->firstOrCreate(['from_unit' => 'kg', 'to_unit' => 'lb'], ['factor' => 2.20462]);
        YarnCount::query()->firstOrCreate(['code' => '2/32-NE-CO'], ['name' => '2/32 Ne cotton', 'system' => 'Ne', 'ply' => 2, 'composition' => '100% cotton']);
        $cat = ProductCategory::query()->firstOrCreate(['code' => 'SWEATER'], ['name' => 'Sweaters', 'name_bn' => 'সোয়েটার', 'slug' => 'sweaters']);
        ProductCategory::query()->firstOrCreate(['code' => 'POLO'], ['name' => 'Polo', 'name_bn' => 'পোলো', 'slug' => 'polo']);

        $buyer = Buyer::query()->firstOrCreate(['code' => 'NW'], [
            'name' => 'Northwind Apparel', 'country' => 'Germany', 'payment_terms' => 'LC at sight',
            'incoterm' => 'FOB Chattogram', 'currency' => 'USD', 'default_aql_major' => 2.5,
            'default_aql_minor' => 4, 'status' => 'active', 'branch_id' => $head->id,
            'labeling_rules' => 'Main label, care, size, COO. No brand name on the polybag.',
            'packing_rules' => 'Solid size, 12 pieces per carton, barcode on short side.',
        ]);
        $buyerUser = $this->user('buyer@knitwear.test', 'Northwind Buyer', 'Buyer', $head->id, $password);
        $buyerUser->update(['buyer_id' => $buyer->id]);

        $spinner = Supplier::query()->firstOrCreate(['code' => 'MEGHNA'], [
            'name' => 'Meghna Spinning', 'type' => 'yarn_spinner', 'approved' => true, 'country' => 'Bangladesh',
            'payment_terms' => '60 days', 'status' => 'active', 'rating' => 4.2, 'branch_id' => $head->id,
        ]);
        $supplierUser = $this->user('supplier@knitwear.test', 'Meghna Desk', 'Supplier', $head->id, $password);
        $supplierUser->update(['supplier_id' => $spinner->id]);

        $slugger = app(Slugger::class);
        $sweater = Style::query()->firstOrCreate(['code' => 'SW-2401'], [
            'name' => 'Oatmeal crew, 12 gauge', 'name_bn' => 'ওটমিল ক্রু, ১২ গেজ',
            'slug' => $slugger->from('oatmeal crew 12 gauge'), 'buyer_id' => $buyer->id,
            'product_category_id' => $cat->id, 'season' => 'AW26', 'product_type' => 'pullover',
            'gender' => 'men', 'construction' => 'fully-fashioned', 'production_route' => 'sweater',
            'gauge' => '12 GG', 'stitch' => 'half cardigan', 'composition' => '100% cotton',
            'yarn_count' => '2/32 Ne', 'ply' => 2, 'description' => 'Fully fashioned crew with 1x1 rib, linked and light garment wash.',
            'wash_care' => 'Machine wash cold, dry flat.', 'packing_method' => 'fold, solid size',
            'status' => 'active', 'branch_id' => $head->id,
            'dpp' => ['fibre' => 'cotton', 'origin' => 'Bangladesh', 'repair' => 're-link and mend in house'],
        ]);
        Style::query()->firstOrCreate(['code' => 'PL-110'], [
            'name' => 'Pique polo, 24 gauge', 'slug' => $slugger->from('pique polo 24 gauge'),
            'buyer_id' => $buyer->id, 'season' => 'SS26', 'product_type' => 'polo', 'gender' => 'men',
            'construction' => 'cut-and-sew', 'production_route' => 'cut_and_sew', 'gauge' => '24 GG',
            'stitch' => 'pique', 'composition' => '100% cotton', 'gsm' => 220,
            'description' => 'Two-button pique polo, self collar, side vents.',
            'status' => 'active', 'branch_id' => $head->id,
        ]);

        $bom = Bom::query()->firstOrCreate(['style_id' => $sweater->id, 'version' => 1], ['status' => 'approved', 'approved_at' => now(), 'branch_id' => $head->id]);
        BomLine::query()->firstOrCreate(['bom_id' => $bom->id, 'description' => '2/32 Ne cotton oatmeal'], [
            'item_type' => 'yarn', 'part' => 'body', 'uom' => 'kg', 'qty_per_piece' => 0.285, 'wastage_percent' => 8,
            'colour_name' => 'Oatmeal', 'yarn_count' => '2/32 Ne', 'branch_id' => $head->id,
        ]);
        BomLine::query()->firstOrCreate(['bom_id' => $bom->id, 'description' => 'Main label'], [
            'item_type' => 'label', 'part' => 'neck', 'uom' => 'pcs', 'qty_per_piece' => 1, 'wastage_percent' => 2, 'branch_id' => $head->id,
        ]);

        $template = TnaTemplate::query()->firstOrCreate(['name' => 'Sweater standard', 'route' => 'sweater'], ['is_active' => true, 'branch_id' => $head->id]);
        $steps = [
            ['Lab dip', 'Merchandising', 70], ['Yarn booking', 'Store', 60], ['Yarn in house', 'Store', 45],
            ['Fit approval', 'Sample', 40], ['PP approval', 'Sample', 30], ['Knitting start', 'Knitting', 28],
            ['Linking', 'Linking', 18], ['Washing', 'Washing', 12], ['Finishing', 'Finishing', 8],
            ['Final inspection', 'Quality', 3], ['Ex-factory', 'Shipping', 0],
        ];
        foreach ($steps as $i => [$name, $dept, $offset]) {
            TnaTemplateTask::query()->firstOrCreate(['tna_template_id' => $template->id, 'name' => $name], [
                'department' => $dept, 'offset_days' => $offset, 'sort' => $i, 'branch_id' => $head->id,
            ]);
        }
        $poloTemplate = TnaTemplate::query()->firstOrCreate(['name' => 'Polo cut and sew', 'route' => 'cut_and_sew'], ['is_active' => true]);
        foreach ([
            ['Fabric booking', 'Store', 40], ['Fabric in house', 'Store', 25], ['PP approval', 'Sample', 18],
            ['Cutting', 'Cutting', 14], ['Sewing', 'Sewing', 10], ['Wash', 'Washing', 6],
            ['Finishing', 'Finishing', 4], ['Final inspection', 'Quality', 2], ['Ex-factory', 'Shipping', 0],
        ] as $i => [$name, $dept, $offset]) {
            TnaTemplateTask::query()->firstOrCreate(['tna_template_id' => $poloTemplate->id, 'name' => $name], [
                'department' => $dept, 'offset_days' => $offset, 'sort' => $i,
            ]);
        }

        Machine::query()->firstOrCreate(['code' => 'SH-12-04'], [
            'name' => 'Shima 12G no.4', 'brand' => 'Shima Seiki', 'kind' => 'flat', 'gauge' => '12',
            'bed_width' => '52 inch', 'status' => 'idle', 'location_name' => 'Knit hall A', 'branch_id' => $head->id,
        ]);
        Machine::query()->firstOrCreate(['code' => 'SJ-24-02'], [
            'name' => 'Single jersey 24G', 'brand' => 'Pailung', 'kind' => 'circular', 'gauge' => '24',
            'feeders' => 90, 'status' => 'idle', 'location_name' => 'Circular hall', 'branch_id' => $head->id,
        ]);

        foreach ([
            ['HOLE', 'Hole', 'গর্ত', 'knit', 'major'],
            ['DROP', 'Dropped stitch', 'ড্রপ স্টিচ', 'knit', 'major'],
            ['LADDER', 'Laddering', 'ল্যাডার', 'knit', 'major'],
            ['SLUB', 'Yarn slub', 'স্লাব', 'yarn', 'minor'],
            ['SHADE', 'Shade bar', 'শেড বার', 'yarn', 'major'],
            ['RIB', 'Uneven rib', 'অসমান রিব', 'link', 'minor'],
            ['TWIST', 'Twisted link', 'বাঁকা লিংক', 'link', 'major'],
            ['STAIN', 'Stain', 'দাগ', 'wash', 'major'],
            ['PUCK', 'Seam puckering', 'পাকারিং', 'sew', 'minor'],
        ] as [$code, $name, $bn, $catName, $severity]) {
            Defect::query()->firstOrCreate(['code' => $code], [
                'name' => $name, 'name_bn' => $bn, 'category' => $catName, 'default_severity' => $severity,
            ]);
        }

        $types = [
            ['manual', 'issues', 'Raised from the floor', 'Merchandiser', 240, 'minor'],
            ['inquiry_silent', 'crm', 'Inquiry unanswered', 'Merchandiser', 1440, 'major'],
            ['quotation_expiry', 'crm', 'Quotation validity ending', 'Merchandiser', 1440, 'minor'],
            ['techpack_revised', 'styles', 'Tech pack revised after PO', 'Merchandiser', 480, 'major'],
            ['sample_delay', 'sampling', 'Sample delayed', 'Sample In-charge', 480, 'major'],
            ['sample_comment', 'sampling', 'Buyer comment not addressed', 'Sample In-charge', 1440, 'major'],
            ['measurement_out', 'sampling', 'Measurement out of tolerance', 'Sample In-charge', 480, 'major'],
            ['margin_drop', 'costing', 'Margin below threshold', 'Merchandising Manager', 1440, 'major'],
            ['tna_late', 'orders', 'TNA task overdue', 'Merchandiser', 240, 'major'],
            ['ship_risk', 'orders', 'Ship date at risk', 'General Manager', 240, 'critical'],
            ['po_qty_change', 'orders', 'PO quantity changed after yarn booking', 'Merchandiser', 480, 'major'],
            ['bom_missing', 'bom', 'BOM missing or not approved', 'Merchandiser', 240, 'critical'],
            ['yarn_short', 'bom', 'Material shortfall', 'Store In-charge', 480, 'major'],
            ['consumption_variance', 'bom', 'Consumption above tolerance', 'Knitting Manager', 1440, 'major'],
            ['supplier_late', 'purchase', 'Supplier delivery late', 'Purchase Manager', 480, 'major'],
            ['price_variance', 'purchase', 'Price variance against PO', 'Purchase Manager', 1440, 'major'],
            ['shade_mismatch', 'yarn_store', 'Shade or count mismatch', 'Store In-charge', 120, 'critical'],
            ['short_weight', 'yarn_store', 'Short weight at GRN', 'Store In-charge', 240, 'major'],
            ['lot_mix', 'yarn_store', 'Two dye lots on one panel order', 'Knitting Manager', 60, 'critical'],
            ['machine_idle', 'knitting', 'Machine idle', 'Knitting Supervisor', 30, 'major'],
            ['dropped_stitch', 'knitting', 'Dropped stitch or hole', 'Knitting Supervisor', 60, 'major'],
            ['wrong_program', 'knitting', 'Wrong knit program', 'Knit Programmer', 60, 'critical'],
            ['linking_defect', 'linking', 'Linking defect repeating', 'Linking Supervisor', 120, 'major'],
            ['wip_aging', 'linking', 'Bundle sitting in WIP', 'Linking Supervisor', 240, 'minor'],
            ['shrinkage', 'washing', 'Shrinkage out of limit', 'Washing/Dyeing Manager', 240, 'major'],
            ['dhu_high', 'quality', 'DHU above target', 'Quality Manager', 120, 'major'],
            ['inspection_fail', 'quality', 'Inspection failed', 'Final Auditor', 60, 'critical'],
            ['calibration_due', 'quality', 'Calibration due', 'Quality Manager', 1440, 'minor'],
            ['carton_mismatch', 'packing', 'Carton content mismatch', 'Packing In-charge', 120, 'major'],
            ['document_discrepancy', 'packing', 'Shipping document discrepancy', 'Shipping Manager', 240, 'major'],
            ['lc_expiry', 'commercial', 'LC expiry near', 'Commercial Manager', 1440, 'critical'],
            ['btb_limit', 'commercial', 'Back-to-back limit exceeded', 'Commercial Manager', 240, 'critical'],
            ['unmatched_bill', 'accounts', 'Bill not matched to GRN', 'Accountant', 1440, 'major'],
            ['absenteeism', 'hr', 'Absenteeism spike', 'HR Manager', 240, 'major'],
            ['ot_limit', 'hr', 'Overtime above limit', 'HR Manager', 240, 'major'],
            ['licence_expiry', 'compliance', 'Licence expiring', 'Compliance Officer', 1440, 'critical'],
            ['finding_overdue', 'compliance', 'Audit finding overdue', 'Compliance Officer', 1440, 'major'],
            ['pm_overdue', 'maintenance', 'Preventive maintenance overdue', 'Maintenance Engineer', 1440, 'major'],
            ['capacity_overload', 'cutting', 'Capacity overload', 'Industrial Engineer', 1440, 'major'],
        ];
        foreach ($types as [$code, $module, $name, $owner, $sla, $severity]) {
            IssueType::query()->firstOrCreate(['code' => $code], [
                'module' => $module, 'name' => $name, 'default_owner_role' => $owner,
                'sla_minutes' => $sla, 'severity' => $severity, 'is_active' => true,
                'solution_template' => 'Check the source document, contain the lot, and record the cause before the next shift.',
            ]);
        }

        foreach ([
            ['inquiry_unanswered', 'Inquiry unanswered', 'crm', ['hours' => 24]],
            ['lc_expiry', 'LC expiry watch', 'commercial', ['days' => 15]],
            ['machine_idle', 'Machine idle', 'knitting', ['minutes' => 30]],
            ['margin_floor', 'Margin floor', 'costing', ['percent' => 8]],
        ] as [$key, $name, $module, $thresholds]) {
            AutomationRule::query()->firstOrCreate(['key' => $key], [
                'name' => $name, 'module' => $module, 'enabled' => true, 'thresholds' => $thresholds,
            ]);
        }

        ExchangeRate::query()->firstOrCreate(['currency' => 'USD', 'rate_date' => now()->toDateString()], ['rate_to_bdt' => 120]);
        ExchangeRate::query()->firstOrCreate(['currency' => 'EUR', 'rate_date' => now()->toDateString()], ['rate_to_bdt' => 130]);
        ExchangeRate::query()->firstOrCreate(['currency' => 'GBP', 'rate_date' => now()->toDateString()], ['rate_to_bdt' => 152]);
        Holiday::query()->firstOrCreate(['holiday_date' => '2026-12-16', 'name' => 'Victory Day'], ['name_bn' => 'বিজয় দিবস']);

        $yarn = ChartOfAccount::query()->firstOrCreate(['code' => '1400'], ['name' => 'Yarn inventory', 'type' => 'asset']);
        $grni = ChartOfAccount::query()->firstOrCreate(['code' => '2100'], ['name' => 'Goods received not invoiced', 'type' => 'liability']);
        PostingRule::query()->firstOrCreate(['event' => 'grn'], [
            'debit_account_id' => $yarn->id, 'credit_account_id' => $grni->id, 'active' => true,
        ]);

        $order = BuyerOrder::query()->firstOrCreate(['buyer_po_no' => 'NW-PO-8841'], [
            'number' => 'BPO-DRAFT', 'buyer_id' => $buyer->id, 'style_id' => $sweater->id,
            'currency' => 'USD', 'quantity' => 2400, 'ex_factory_date' => now()->addDays(75)->toDateString(),
            'ship_date' => now()->addDays(80)->toDateString(), 'port' => 'Chattogram', 'mode' => 'sea',
            'incoterm' => 'FOB', 'status' => 'draft', 'production_route' => 'sweater', 'unit_price' => 8.4,
            'branch_id' => $head->id,
        ]);
        if ($order->status === 'draft') {
            app(DocumentWorkflow::class)->confirm($order);
        }

        $this->seedFloorExceptions($head, $buyer, $sweater, $spinner, $order);

        $polo = Style::query()->where('code', 'PL-110')->first();
        $poloOrder = BuyerOrder::query()->firstOrCreate(['buyer_po_no' => 'NW-PO-9012'], [
            'number' => 'BPO-DRAFT-POLO', 'buyer_id' => $buyer->id, 'style_id' => $polo->id,
            'currency' => 'USD', 'quantity' => 5000, 'ex_factory_date' => now()->addDays(40)->toDateString(),
            'ship_date' => now()->addDays(45)->toDateString(), 'port' => 'Chattogram', 'mode' => 'sea',
            'incoterm' => 'FOB', 'status' => 'draft', 'production_route' => 'cut_and_sew', 'unit_price' => 4.15,
            'branch_id' => $head->id,
        ]);
        if ($poloOrder->status === 'draft') {
            app(DocumentWorkflow::class)->confirm($poloOrder);
        }

        foreach ([
            'home' => ['Northwind-ready knitwear from Narayanganj', 'Fully fashioned sweaters and circular knit polos, made on the same floor that still keeps a yarn book.'],
            'about' => ['The mill', 'We knit, link, mend, wash and pack in our own units. The office still starts the day with a TNA board. This system keeps that board, and it shouts when a date slips.'],
            'capabilities' => ['What the floor can do', 'Flat bed 3 to 16 gauge, circular jersey and pique, linking, mending, garment wash, and a cut-and-sew polo line.'],
            'sustainability' => ['Compliance is a register, not a poster', 'ETP readings, licence dates and audit findings sit next to the orders they protect.'],
            'quality' => ['AQL the buyer actually wrote', 'Final audit uses the buyer AQL, not a factory average. A fail holds the shipment.'],
            'sampling' => ['Samples, with the revision history attached', 'Proto, fit, size set, PP and TOP. A rejected point stays open until someone closes it.'],
        ] as $slug => [$title, $body]) {
            CmsPage::query()->firstOrCreate(['slug' => $slug], ['title' => $title, 'body' => $body, 'status' => 'published']);
        }
        Faq::query()->firstOrCreate(['question' => 'What is the usual sweater lead time?'], [
            'answer' => 'For a 12 gauge cotton program with yarn already dyed, plan about 75 to 90 days from PP approval to ex-factory.',
            'sort' => 1,
        ]);
    }

    private function seedFloorExceptions(Branch $head, Buyer $buyer, Style $sweater, Supplier $spinner, BuyerOrder $order): void
    {
        $engine = app(IssueEngine::class);
        $workflow = app(DocumentWorkflow::class);
        $job = ProductionOrder::query()->where('buyer_order_id', $order->id)->first();
        $machine = Machine::query()->where('code', 'SH-12-04')->first();

        $sample = SampleOrder::query()->firstOrCreate(['number' => 'SMP-FIT-12'], [
            'style_id' => $sweater->id, 'buyer_id' => $buyer->id, 'type' => 'fit', 'status' => 'rejected',
            'requested_on' => now()->subDays(12)->toDateString(), 'promised_on' => now()->subDays(4)->toDateString(),
            'revision' => 2, 'yarn_grams' => 280, 'branch_id' => $head->id,
        ]);
        SampleComment::query()->firstOrCreate(
            ['sample_order_id' => $sample->id, 'point' => 'Chest, size M'],
            ['comment' => 'Chest is 1.4 cm over spec. Re-knit the front panel before the next fit send.', 'status' => 'open', 'due_on' => now()->subDay()->toDateString(), 'branch_id' => $head->id],
        );
        $engine->raise([
            'type_code' => 'sample_comment',
            'title' => 'Fit sample chest still open on SW-2401',
            'source_type' => $sample->getMorphClass(),
            'source_id' => $sample->id,
            'branch_id' => $head->id,
        ]);

        $yarnPo = PurchaseOrder::query()->firstOrCreate(['number' => 'YPO-LATE-17'], [
            'supplier_id' => $spinner->id, 'currency' => 'BDT', 'total' => 684000, 'status' => 'approved',
            'expected_on' => now()->subDays(6)->toDateString(), 'notes' => '2/32 Ne oatmeal, 570 kg', 'branch_id' => $head->id,
        ]);
        $engine->raise([
            'type_code' => 'supplier_late',
            'title' => 'Meghna yarn PO YPO-LATE-17 is past the promised date',
            'source_type' => $yarnPo->getMorphClass(),
            'source_id' => $yarnPo->id,
            'supplier_id' => $spinner->id,
            'branch_id' => $head->id,
        ]);

        $grn = Grn::query()->firstOrCreate(['number' => 'GRN-SHADE-9'], [
            'supplier_id' => $spinner->id, 'challan_no' => 'MS-441', 'received_on' => now()->toDateString(),
            'status' => 'draft', 'branch_id' => $head->id,
        ]);
        $line = GrnLine::query()->firstOrCreate(['grn_id' => $grn->id, 'lot_no' => 'DYE-44'], [
            'item_code' => '2/32 Ne cotton oatmeal', 'qty' => 120, 'uom' => 'kg', 'net_weight' => 118.4,
            'shade' => 'oatmeal', 'branch_id' => $head->id,
        ]);
        YarnInspection::query()->firstOrCreate(['grn_line_id' => $line->id], [
            'shade_ok' => false, 'count_ok' => true, 'decision' => 'reject',
            'notes' => 'Shade is warmer than the approved card. Do not issue to knitting.',
            'branch_id' => $head->id,
        ]);
        if ($grn->status !== 'posted') {
            $workflow->post($grn);
        }

        if ($machine) {
            DowntimeLog::query()->firstOrCreate(
                ['machine_id' => $machine->id, 'reason' => 'needle break'],
                ['started_at' => now()->subMinutes(45), 'minutes' => 45, 'note' => 'Front bed, system 2.', 'branch_id' => $head->id],
            );
            $engine->raise([
                'type_code' => 'machine_idle',
                'title' => 'Shima 12G no.4 stopped for a needle break',
                'source_type' => $machine->getMorphClass(),
                'source_id' => $machine->id,
                'machine_id' => $machine->id,
                'branch_id' => $head->id,
            ]);
        }

        if ($job) {
            $workflow->mrp($job);
            $inspection = Inspection::query()->firstOrCreate(['number' => 'INS-FINAL-3'], [
                'production_order_id' => $job->id, 'type' => 'final', 'lot_size' => 2400,
                'aql_major' => 2.5, 'aql_minor' => 4, 'major_found' => 14, 'minor_found' => 6,
                'critical_found' => 0, 'status' => 'open', 'branch_id' => $head->id,
            ]);
            if ($inspection->result === null) {
                $workflow->judge($inspection);
            }
        }
    }

    /**
     * @param  array<string, list<Permission>>  $byModule
     * @param  list<string>  $modules
     */
    private function grant(string $role, array $byModule, array $modules): void
    {
        $perms = collect($modules)->flatMap(fn (string $module) => $byModule[$module] ?? [])->all();
        Role::findByName($role)->syncPermissions($perms);
    }

    private function user(string $email, string $name, string $role, int $branchId, string $password): User
    {
        $user = User::query()->updateOrCreate(['email' => $email], [
            'name' => $name, 'password' => $password, 'branch_id' => $branchId, 'is_active' => true, 'locale' => 'en',
        ]);
        $user->syncRoles([$role]);

        return $user;
    }
}
