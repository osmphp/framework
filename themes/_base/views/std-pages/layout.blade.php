<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($canonical_url)
        <link rel="canonical" href="{{ $canonical_url }}">
    @endif

    <link href="{{ $asset('styles.css') }}" rel="stylesheet">

    @if(isset($head))
        {{ $head }}
    @else
        @include('std-pages::head')
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    @if(isset($header))
        {{ $header }}
    @else
        @include('std-pages::header')
    @endif

    {{ $slot }}

    @if(isset($footer))
        {{ $footer }}
    @else
        @include('std-pages::footer')
    @endif

    <script src="{{ $asset('scripts.js') }}"></script>
</body>
</html>