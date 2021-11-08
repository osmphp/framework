<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif

    <link href="{{ $asset('styles.css') }}" rel="stylesheet">

    @include('std-pages::head')
    {{ $head ?? '' }}

    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    @include('std-pages::header')
    {{ $header ?? '' }}

    {{ $slot }}

    @include('std-pages::footer')
    {{ $footer ?? '' }}

    <script src="{{ $asset('scripts.js') }}"></script>
</body>
</html>