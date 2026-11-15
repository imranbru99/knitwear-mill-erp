@extends('layouts.site')
@section('title', 'Buyer portal')
@section('content')
<section class="hero">
    <h1>Your orders</h1>
    @if(session('status'))<p class="status">{{ session('status') }}</p>@endif
    @foreach($orders as $order)
        <article class="sheet">
            <h2>{{ $order->number }} · {{ $order->buyer_po_no }}</h2>
            <p>Status {{ $order->status }}. Ship {{ $order->ship_date?->toDateString() }}. Qty {{ $order->quantity }}.</p>
        </article>
    @endforeach
    <h2>Samples</h2>
    @foreach($samples as $sample)
        <article class="sheet">
            <h2>{{ $sample->number }} · {{ $sample->type }} · {{ $sample->status }}</h2>
            <form method="post" action="{{ route('portal.buyer.comment') }}">
                @csrf
                <input type="hidden" name="sample_order_id" value="{{ $sample->id }}">
                <label>Point <input name="point" required></label>
                <label>Comment <textarea name="comment" required></textarea></label>
                <button>Send comment</button>
            </form>
        </article>
    @endforeach
</section>
@endsection
