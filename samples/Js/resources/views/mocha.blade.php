<?php
/* @var \Osm\Samples\Js\Views\TestRunner $view */
?>
@if ($view->content)
    @include($view->content)
@endif
<div id="mocha"></div>
<script src="{{ osm_asset('Osm_Samples_Js/chai.js') }}"></script>
<script src="{{ osm_asset('Osm_Samples_Js/mocha.js') }}"></script>

