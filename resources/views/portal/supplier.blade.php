@extends('layouts.site')
@section('title', 'Supplier portal')
@section('content')
<section class="hero">
    <h1>Yarn and trim orders</h1>
    @forelse($orders as $order)
        <article class="sheet">
            <h2>{{ $order->number }}</h2>
            <p>Status {{ $order->status }}. Expected {{ $order->expected_on?->toDateString() }}. Total {{ $order->total }} {{ $order->currency }}.</p>
        </article>
    @empty
        <p>No purchase orders on your account yet.</p>
    @endforelse
</section>
@endsection
