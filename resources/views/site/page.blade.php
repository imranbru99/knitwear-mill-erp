@extends('layouts.site')
@section('title', $page->title)
@section('content')
<article class="hero sheet">
    <p class="kicker">Mill note</p>
    <h1>{{ $page->title }}</h1>
    <p>{{ $page->body }}</p>
</article>
@endsection
