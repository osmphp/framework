<?php
global $osm_app; /* @var \Osm\Core\App $osm_app */

?>
<!doctype html>
<html lang="en">
<head>
    <title>@yield('title') | {{ $osm_app->http->title }}</title>

    @section('head')
        @include('std-pages::head')
    @show

    <link href="{{ $osm_app->asset('styles.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    @section('header')
        @include('std-pages::header')
    @show

    @yield('content')

    @section('footer')
        @include('std-pages::footer')
    @show

    <script src="{{ $osm_app->asset('scripts.js') }}"></script>
</body>
</html>
