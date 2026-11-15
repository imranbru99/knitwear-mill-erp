@extends('layouts.site')
@section('title', 'Inquiry')
@section('content')
<section class="hero">
    <p class="kicker">Merchandising</p>
    <h1>Tell us the gauge, the quantity and the ship window.</h1>
    @if(session('status'))<p class="status">{{ session('status') }}</p>@endif
    <form method="post" action="{{ route('inquiries.store') }}" enctype="multipart/form-data">
        @csrf
        <label class="honeypot">Website <input name="company_website" tabindex="-1" autocomplete="off"></label>
        <label>Name <input name="contact_name" value="{{ old('contact_name') }}" required></label>
        <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
        <label>Phone <input name="phone" value="{{ old('phone') }}"></label>
        <label>Style <input name="style_hint" value="{{ old('style_hint') }}" required></label>
        <label>Product <input name="product_type" value="{{ old('product_type') }}" placeholder="pullover, cardigan, polo"></label>
        <label>Quantity <input type="number" name="quantity" value="{{ old('quantity', 1200) }}" required></label>
        <label>Target price <input name="target_price" value="{{ old('target_price') }}"></label>
        <label>Currency
            <select name="currency"><option>USD</option><option>EUR</option><option>GBP</option><option>BDT</option></select>
        </label>
        <label>Ship window <input name="timeline" value="{{ old('timeline') }}"></label>
        <label>Notes <textarea name="notes">{{ old('notes') }}</textarea></label>
        <label>Tech pack <input type="file" name="attachment"></label>
        <button>Send inquiry</button>
        @if($errors->any())<p>{{ $errors->first() }}</p>@endif
    </form>
</section>
@endsection
