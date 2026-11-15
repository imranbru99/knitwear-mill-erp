<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Knitwear Factory')</title>
    <meta name="description" content="Fully fashioned sweaters and circular knit polos from a Narayanganj mill.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,620;1,9..144,500&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mill.css') }}">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="bn" href="{{ url()->current() }}?lang=bn">
</head>
<body class="@yield('body')">
    <header class="mast">
        <a class="mark" href="{{ route('home') }}">Fatullah Knit</a>
        <nav>
            <a href="{{ route('products') }}">Styles</a>
            <a href="{{ route('capabilities') }}">Floor</a>
            <a href="{{ route('sampling') }}">Sampling</a>
            <a href="{{ route('quality') }}">Quality</a>
            <a href="{{ route('sustainability') }}">Compliance</a>
            <a href="{{ route('careers') }}">Careers</a>
            <a href="{{ route('contact') }}">Inquiry</a>
            <a href="{{ route('locale', app()->getLocale() === 'bn' ? 'en' : 'bn') }}">{{ app()->getLocale() === 'bn' ? 'EN' : 'বাং' }}</a>
            @auth
                <a href="{{ route('portal.buyer') }}">Portal</a>
            @else
                <a href="{{ route('login') }}">Sign in</a>
            @endauth
        </nav>
    </header>
    <main>@yield('content')</main>
    <footer class="colophon">
        <p>Fatullah, Narayanganj. Flat-bed sweaters and circular knit polos. Friday is the weekly holiday.</p>
        <p><a href="{{ route('privacy') }}">Privacy</a> · <a href="{{ route('terms') }}">Terms</a> · <a href="{{ route('faq') }}">FAQ</a></p>
    </footer>
</body>
</html>
