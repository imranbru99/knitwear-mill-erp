<?php

namespace App\Services\Merchandising;

use App\Models\Holiday;
use App\Models\ProductionOrder;
use App\Models\TnaTask;
use App\Models\TnaTemplate;
use Carbon\Carbon;

class TnaService
{
    public function generate(ProductionOrder $order): void
    {
        $order->load('buyerOrder');
        $template = TnaTemplate::query()
            ->where('route', $order->route)
            ->where('is_active', true)
            ->orderByDesc('id')
            ->first();

        if (! $template) {
            return;
        }

        $ship = Carbon::parse($order->buyerOrder->ship_date)->startOfDay();
        TnaTask::query()->where('production_order_id', $order->id)->forceDelete();

        foreach ($template->tnaTemplateTasks()->orderBy('sort')->get() as $task) {
            $end = $this->subtractWorkingDays($ship->copy(), (int) $task->offset_days);
            TnaTask::query()->create([
                'production_order_id' => $order->id,
                'name' => $task->name,
                'department' => $task->department,
                'planned_end' => $end->toDateString(),
                'planned_start' => $this->subtractWorkingDays($end->copy(), 1)->toDateString(),
                'baseline_end' => $end->toDateString(),
                'status' => 'pending',
                'sort' => $task->sort,
                'branch_id' => $order->branch_id,
            ]);
        }
    }

    public function shiftOpenTasks(ProductionOrder $order, int $lateDays): void
    {
        TnaTask::query()
            ->where('production_order_id', $order->id)
            ->where('status', '!=', 'done')
            ->orderBy('sort')
            ->each(function (TnaTask $task) use ($lateDays): void {
                $end = $this->addWorkingDays(Carbon::parse($task->planned_end), $lateDays);
                $task->update([
                    'planned_end' => $end->toDateString(),
                    'status' => $end->lt(today()) ? 'late' : $task->status,
                ]);
            });
    }

    public function subtractWorkingDays(Carbon $date, int $days): Carbon
    {
        $cursor = $date->copy();
        $left = $days;
        while ($left > 0) {
            $cursor->subDay();
            if ($this->isWorkingDay($cursor)) {
                $left--;
            }
        }

        return $cursor;
    }

    public function addWorkingDays(Carbon $date, int $days): Carbon
    {
        $cursor = $date->copy();
        $left = $days;
        while ($left > 0) {
            $cursor->addDay();
            if ($this->isWorkingDay($cursor)) {
                $left--;
            }
        }

        return $cursor;
    }

    public function isWorkingDay(Carbon $date): bool
    {
        if ($date->isFriday()) {
            return false;
        }

        return ! Holiday::query()->whereDate('holiday_date', $date->toDateString())->exists();
    }
}
