<?php
/* @var \Osm\Ui\SnackBars\Views\SnackBar $view */
use Osm\Ui\Buttons\Views\Button;
?>
@component ('Osm_Ui_SnackBars.message', ['view' => $view])
    @slot('side')
        @include(Button::new(['alias' => 'close', 'title' => osm_t("Close"), 'modifier' => '-light']))
    @endslot
@endcomponent
