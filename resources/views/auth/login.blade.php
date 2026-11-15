@extends('layouts.site')
@section('title', 'Sign in')
@section('content')
<section class="hero sheet">
    <h1>Mill sign in</h1>
    <form method="post" action="{{ url('/login') }}">
        @csrf
        <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button>Enter</button>
        @if($errors->any())<p>{{ $errors->first() }}</p>@endif
    </form>
</section>
@endsection
