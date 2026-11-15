@extends('layouts.site')
@section('title', 'Assistant')
@section('content')
<section class="hero sheet">
    <h1>Ask the mill</h1>
    <p>Try “which orders are at risk” or “style SW-2401 status”.</p>
    <form method="post" action="{{ route('assistant.ask') }}">
        @csrf
        <label>Question <input name="question" value="{{ $question ?? '' }}" required></label>
        <button>Ask</button>
    </form>
    @if($answer)<p>{{ $answer }}</p>@endif
</section>
@endsection
