<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif

    <link href="{{ $asset('styles.css') }}" rel="stylesheet">
    @include('std-pages::head')
    {{ $head ?? '' }}

    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    @if(isset($header))
    <header class="container mx-auto fixed top-0 left-0 right-0 z-10">
        {{ $header }}
    </header>
    <div class="h-10"></div>
    @endif

    <div class="container mx-auto px-4 grid grid-cols-12 gap-4">
        {{ $slot }}
    </div>

    @if(isset($footer))
    <footer class="container mx-auto">
        {{ $footer }}
    </footer>
    @endif

    <script src="{{ $asset('scripts.js') }}"></script>
</body>
</html>