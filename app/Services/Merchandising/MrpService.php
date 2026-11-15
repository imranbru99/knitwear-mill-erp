<?php

namespace App\Services\Merchandising;

use App\Models\Bom;
use App\Models\MaterialRequirement;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrderLine;
use App\Services\Inventory\StockService;
use App\Services\Issues\IssueEngine;
use Illuminate\Support\Collection;

class MrpService
{
    public function __construct(
        private StockService $stock,
        private IssueEngine $issues,
    ) {}

    /**
     * @return Collection<int, MaterialRequirement>
     */
    public function explode(ProductionOrder $order): Collection
    {
        $order->load('buyerOrder.style');
        $bom = Bom::query()
            ->where('style_id', $order->buyerOrder->style_id)
            ->where('status', 'approved')
            ->orderByDesc('version')
            ->first();

        if (! $bom) {
            $this->issues->raise([
                'type_code' => 'bom_missing',
                'title' => 'No approved BOM for '.$order->number,
                'source_type' => $order->getMorphClass(),
                'source_id' => $order->id,
                'production_order_id' => $order->id,
                'branch_id' => $order->branch_id,
            ]);

            return collect();
        }

        $made = collect();
        foreach ($bom->bomLines as $line) {
            $required = round((float) $order->quantity * (float) $line->qty_per_piece * (1 + ((float) $line->wastage_percent / 100)), 3);
            $available = $this->stock->onHand($line->item_type, $line->description, $order->branch_id);
            $incoming = (float) PurchaseOrderLine::query()
                ->where('description', $line->description)
                ->whereColumn('received_qty', '<', 'qty')
                ->selectRaw('coalesce(sum(qty - received_qty), 0) as open_qty')
                ->value('open_qty');
            $shortfall = round(max(0, $required - $available - $incoming), 3);

            $row = MaterialRequirement::query()->updateOrCreate(
                [
                    'production_order_id' => $order->id,
                    'description' => $line->description,
                ],
                [
                    'item_type' => $line->item_type,
                    'uom' => $line->uom,
                    'required_qty' => $required,
                    'available_qty' => $available,
                    'incoming_qty' => $incoming,
                    'reserved_qty' => 0,
                    'shortfall_qty' => $shortfall,
                    'status' => $shortfall > 0 ? 'short' : 'covered',
                    'branch_id' => $order->branch_id,
                ],
            );

            if ($shortfall > 0) {
                $this->issues->raise([
                    'type_code' => 'yarn_short',
                    'title' => 'Short '.$line->description.' for '.$order->number,
                    'description' => 'Need '.$required.' '.$line->uom.', short '.$shortfall.'.',
                    'source_type' => $row->getMorphClass(),
                    'source_id' => $row->id,
                    'production_order_id' => $order->id,
                    'branch_id' => $order->branch_id,
                ]);
            }

            $made->push($row);
        }

        return $made;
    }
}
