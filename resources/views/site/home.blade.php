@extends('layouts.site')
@section('title', 'Fatullah Knit')
@section('body', 'is-home')
@section('content')
<div class="landing">
    <section class="stage">
        <div class="stage-copy">
            <p class="kicker">Fatullah, Narayanganj</p>
            <h1>{{ $page->title ?? 'Sweaters with the rib still tight.' }}</h1>
            <p class="lede">{{ $page->body ?? 'Fully fashioned crews, cardigans and linked trims on one floor. Pique polos on the circular line next door.' }}</p>
            <div class="stage-actions">
                <a class="button" href="{{ route('contact') }}">Request a program</a>
                <a class="button ghost" href="{{ route('products') }}">View the styles</a>
            </div>
        </div>
        <aside class="stage-panel" aria-hidden="true">
            <div class="rib"></div>
            <div class="panel-meta">
                <p><span>Gauge</span> 3–16 GG</p>
                <p><span>Beds</span> Flat and circular</p>
                <p><span>Finish</span> Link, mend, wash</p>
            </div>
        </aside>
    </section>

    <section class="metrics" aria-label="Mill facts">
        <p><strong>12 GG</strong><span>Cotton crews and cardigans</span></p>
        <p><strong>24 GG</strong><span>Pique polo, cut and sew</span></p>
        <p><strong>AQL</strong><span>The buyer’s level, not ours</span></p>
        <p><strong>FOB</strong><span>Chattogram, sea</span></p>
    </section>

    <section class="craft">
        <header class="section-head">
            <div>
                <p class="kicker">The floor</p>
                <h2>Knit, then the work that makes it a garment.</h2>
            </div>
        </header>
        <ol>
            <li>
                <span>01</span>
                <h3>Flat bed</h3>
                <p>Fully fashioned panels on 3 to 16 gauge. Half cardigan, full cardigan, rib 1x1 and 2x2, the program loaded on the machine that can actually knit it.</p>
            </li>
            <li>
                <span>02</span>
                <h3>Link and mend</h3>
                <p>Body, sleeve and collar joined on the linking bed. A hole is named, mended, and sent back if it comes through a second time.</p>
            </li>
            <li>
                <span>03</span>
                <h3>Wash and audit</h3>
                <p>Garment wash in house. Final inspection uses the buyer AQL. A fail holds the cartons.</p>
            </li>
        </ol>
    </section>

    <section class="collection">
        <header class="section-head">
            <div>
                <p class="kicker">On the table</p>
                <h2>Styles open for a program.</h2>
            </div>
            <a href="{{ route('products') }}">All styles</a>
        </header>
        <div class="looks">
            @forelse($styles as $style)
                <a class="look look-{{ $style->production_route === 'sweater' ? 'sweater' : 'polo' }}" href="{{ route('products.show', $style) }}">
                    <div class="look-swatch"></div>
                    <div class="look-copy">
                        <p>{{ $style->gauge ?: 'Knit' }}</p>
                        <h3>{{ $style->name }}</h3>
                        <span>{{ str_replace('_', ' ', $style->production_route) }} · {{ $style->composition ?: 'Cotton' }}</span>
                    </div>
                </a>
            @empty
                <p class="empty">Styles appear here once merchandising marks them active.</p>
            @endforelse
        </div>
    </section>

    <section class="close">
        <div>
            <p class="kicker">Inquiry</p>
            <h2>Send the tech pack, the gauge and the ship window.</h2>
            <p>A merchandiser answers within one working day. Friday is the mill holiday.</p>
        </div>
        <a class="button" href="{{ route('contact') }}">Start an inquiry</a>
    </section>
</div>
@endsection
