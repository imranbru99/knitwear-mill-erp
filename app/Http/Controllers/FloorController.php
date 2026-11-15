<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\DowntimeLog;
use App\Models\GatePass;
use App\Models\Machine;
use App\Services\Documents\NumberingService;
use App\Services\Issues\IssueEngine;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function show(string $screen)
    {
        abort_unless(in_array($screen, ['knit', 'qc', 'store', 'packing', 'gate'], true), 404);

        return view('floor.screen', [
            'screen' => $screen,
            'machines' => Machine::query()->orderBy('code')->limit(30)->get(),
        ]);
    }

    public function store(Request $request, string $screen, NumberingService $numbering, IssueEngine $issues)
    {
        $data = $request->validate([
            'barcode' => ['nullable', 'string'],
            'machine_id' => ['nullable', 'exists:machines,id'],
            'reason' => ['nullable', 'string', 'max:180'],
            'pieces' => ['nullable', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        if ($screen === 'knit' && ! empty($data['machine_id']) && ! empty($data['reason'])) {
            DowntimeLog::query()->create([
                'machine_id' => $data['machine_id'],
                'reason' => $data['reason'],
                'started_at' => now(),
                'minutes' => 0,
                'note' => $data['note'] ?? null,
            ]);
        }

        if ($screen === 'gate') {
            GatePass::query()->create([
                'number' => $numbering->next('gate_pass'),
                'direction' => 'out',
                'party' => $data['note'] ?? 'Floor gate',
                'purpose' => $data['reason'] ?? 'material',
                'vehicle_no' => $data['barcode'] ?? null,
                'status' => 'open',
            ]);
        }

        if (! empty($data['barcode'])) {
            $bundle = Bundle::query()->where('barcode', $data['barcode'])->first();
            if ($bundle) {
                $bundle->update(['stage' => $screen]);
            }
        }

        if ($request->boolean('raise_issue') && ! empty($data['note'])) {
            $issues->raise([
                'type_code' => 'manual',
                'title' => $data['note'],
                'machine_id' => $data['machine_id'] ?? null,
                'source_type' => 'floor',
                'source_id' => null,
                'idempotent' => false,
            ]);
        }

        return back()->with('status', 'Floor entry saved.');
    }
}
