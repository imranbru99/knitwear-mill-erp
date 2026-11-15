@extends('layouts.site')
@section('title', 'Styles')
@section('content')
<section class="hero">
    <p class="kicker">Catalogue</p>
    <h1>What we can put on a machine.</h1>
    <form method="get">
        <label>Search <input name="q" value="{{ request('q') }}"></label>
        <label>Route
            <select name="route">
                <option value="">Any</option>
                <option value="sweater" @selected(request('route')==='sweater')>Sweater</option>
                <option value="cut_and_sew" @selected(request('route')==='cut_and_sew')>Cut and sew</option>
            </select>
        </label>
        <button>Filter</button>
    </form>
</section>
<div class="grid">
    @foreach($styles as $style)
        <a class="card" href="{{ route('products.show', $style) }}">
            <div class="swatch"></div>
            <h2>{{ $style->name }}</h2>
            <p>{{ $style->code }} · {{ $style->gauge }}</p>
        </a>
    @endforeach
</div>
@endsection
