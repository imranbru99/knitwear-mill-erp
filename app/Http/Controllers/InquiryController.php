<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Services\Documents\NumberingService;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request, NumberingService $numbering)
    {
        if ($request->filled('company_website')) {
            return back();
        }

        $data = $request->validate([
            'contact_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'style_hint' => ['required', 'string', 'max:180'],
            'product_type' => ['nullable', 'string', 'max:80'],
            'quantity' => ['required', 'integer', 'min:1'],
            'target_price' => ['nullable', 'numeric'],
            'currency' => ['required', 'in:USD,EUR,GBP,BDT'],
            'timeline' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
        ]);

        $path = null;
        if ($request->file('attachment')) {
            $path = $request->file('attachment')->store('inquiries');
        }

        Inquiry::query()->create([
            'number' => $numbering->next('inquiry'),
            'source' => 'website',
            'contact_name' => $data['contact_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'style_hint' => $data['style_hint'],
            'product_type' => $data['product_type'] ?? null,
            'quantity' => $data['quantity'],
            'target_price' => $data['target_price'] ?? null,
            'currency' => $data['currency'],
            'timeline' => $data['timeline'] ?? null,
            'notes' => trim(($data['notes'] ?? '').($path ? "\nFile: ".$path : '')),
            'status' => 'new',
        ]);

        return back()->with('status', 'Inquiry received. A merchandiser will answer within one working day.');
    }
}
