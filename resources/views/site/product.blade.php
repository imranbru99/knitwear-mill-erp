@extends('layouts.site')
@section('title', $style->name)
@section('content')
<article class="hero">
    <p class="kicker">{{ $style->code }}</p>
    <h1>{{ $style->name }}</h1>
    <div class="swatch"></div>
    <p>{{ $style->description }}</p>
    <ul>
        <li>Route: {{ str_replace('_', ' ', $style->production_route) }}</li>
        <li>Construction: {{ $style->construction }}</li>
        <li>Gauge: {{ $style->gauge }}</li>
        <li>Stitch: {{ $style->stitch }}</li>
        <li>Yarn: {{ $style->composition }} {{ $style->yarn_count }}</li>
        <li>GSM: {{ $style->gsm }}</li>
        <li>Care: {{ $style->wash_care }}</li>
    </ul>
    <script type="application/ld+json">
        {!! json_encode(['@context' => 'https://schema.org', '@type' => 'Product', 'name' => $style->name, 'sku' => $style->code, 'description' => $style->description, 'material' => $style->composition], JSON_UNESCAPED_UNICODE) !!}
    </script>
    <p><a class="button" href="{{ route('contact') }}">Request a quote for {{ $style->code }}</a></p>
</article>
@endsection
