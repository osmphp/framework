<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    <link href="{{ $asset('styles.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    {{ $slot }}
    <script src="{{ $asset('scripts.js') }}"></script>
</body>
</html>