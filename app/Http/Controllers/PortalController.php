<?php

namespace App\Http\Controllers;

use App\Models\BuyerOrder;
use App\Models\Issue;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\SampleComment;
use App\Models\SampleOrder;
use App\Models\Style;
use App\Support\Integrations\AiClient;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function buyer(Request $request)
    {
        abort_unless($request->user()->hasRole('Buyer') || $request->user()->sees_all_branches, 403);
        $buyerId = $request->user()->buyer_id;
        $orders = BuyerOrder::query()->when($buyerId, fn ($q) => $q->where('buyer_id', $buyerId))->latest('id')->get();
        $samples = SampleOrder::query()->when($buyerId, fn ($q) => $q->where('buyer_id', $buyerId))->latest('id')->get();

        return view('portal.buyer', ['orders' => $orders, 'samples' => $samples]);
    }

    public function comment(Request $request)
    {
        abort_unless($request->user()->hasRole('Buyer') || $request->user()->sees_all_branches, 403);
        $data = $request->validate([
            'sample_order_id' => ['required', 'exists:sample_orders,id'],
            'point' => ['required', 'string', 'max:120'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);
        SampleComment::query()->create([
            'sample_order_id' => $data['sample_order_id'],
            'point' => $data['point'],
            'comment' => $data['comment'],
            'status' => 'open',
            'due_on' => now()->addDays(3)->toDateString(),
        ]);

        return back()->with('status', 'Comment sent to the sample room.');
    }

    public function supplier(Request $request)
    {
        abort_unless($request->user()->hasRole('Supplier') || $request->user()->sees_all_branches, 403);
        $orders = PurchaseOrder::query()
            ->when($request->user()->supplier_id, fn ($q) => $q->where('supplier_id', $request->user()->supplier_id))
            ->latest('id')
            ->get();

        return view('portal.supplier', ['orders' => $orders]);
    }

    public function assistant()
    {
        return view('portal.assistant', ['answer' => null]);
    }

    public function ask(Request $request, AiClient $ai)
    {
        $question = $request->validate(['question' => ['required', 'string', 'max:500']])['question'];
        $lower = strtolower($question);
        if (str_contains($lower, 'risk')) {
            $rows = ProductionOrder::query()->whereIn('risk', ['amber', 'red'])->get();
            $answer = $rows->isEmpty()
                ? 'No jobs are marked amber or red.'
                : $rows->map(fn ($row) => $row->number.' is '.$row->risk.' for '.$row->quantity.' pieces.')->implode(' ');
        } elseif (preg_match('/style\s+([a-z0-9-]+)/i', $question, $match)) {
            $style = Style::query()->where('code', $match[1])->orWhere('slug', $match[1])->first();
            $answer = $style
                ? $style->code.' '.$style->name.' is '.$style->status.' on the '.$style->production_route.' route, gauge '.$style->gauge.'.'
                : 'No style matches '.$match[1].'.';
        } else {
            $open = Issue::query()->where('status', 'open')->count();
            $context = "Open issues: {$open}. Question: {$question}";
            $answer = $ai->text($context, 'Answer as a knitwear factory manager. If you lack data, say what register to open.')
                ?? 'AI is not configured. Open issues right now: '.$open.'.';
        }

        return view('portal.assistant', ['answer' => $answer, 'question' => $question]);
    }
}
