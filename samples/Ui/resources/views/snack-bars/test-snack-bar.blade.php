<?php
/* @var \Manadev\Ui\SnackBars\Views\SnackBar $view */
use Manadev\Ui\Buttons\Views\Button;
?>
@component ('Manadev_Ui_SnackBars.message', ['view' => $view])
    @slot('side')
        @include(Button::new(['alias' => 'close', 'title' => m_("Close"), 'modifier' => '-light']))
    @endslot
@endcomponent
