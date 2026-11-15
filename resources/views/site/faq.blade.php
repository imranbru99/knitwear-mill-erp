@extends('layouts.site')
@section('title', 'FAQ')
@section('content')
<section class="hero"><h1>Questions the sample room hears.</h1>
@foreach($faqs as $faq)
    <article class="sheet"><h2>{{ $faq->question }}</h2><p>{{ $faq->answer }}</p></article>
@endforeach
</section>
@endsection
