<?php
/* @var \Osm\Ui\SnackBars\Views\SnackBar $view */
use Osm\Ui\Buttons\Views\Button;
?>
@component ('Osm_Ui_SnackBars.message', ['view' => $view])
    @slot('side')
        @include(Button::new(['alias' => 'stack-trace', 'title' => osm_t("Stack Trace"), 'modifier' => '-light']))
    @endslot
@endcomponent
