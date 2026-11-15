@extends('layouts.site')
@section('title', 'Careers')
@section('content')
<section class="hero">
    <h1>Work on the floor.</h1>
    @if(session('status'))<p class="status">{{ session('status') }}</p>@endif
    @forelse($jobs as $job)
        <article class="sheet">
            <h2>{{ $job->title }}</h2>
            <p>{{ $job->department }}</p>
            <p>{{ $job->description }}</p>
            <form method="post" action="{{ route('careers.apply') }}">
                @csrf
                <input type="hidden" name="job_opening_id" value="{{ $job->id }}">
                <label>Name <input name="name" required></label>
                <label>Email <input type="email" name="email" required></label>
                <label>Phone <input name="phone"></label>
                <button>Apply</button>
            </form>
        </article>
    @empty
        <p>No open reqs this week. The knitting hall still takes experienced 12 gauge operators by introduction.</p>
    @endforelse
</section>
@endsection
