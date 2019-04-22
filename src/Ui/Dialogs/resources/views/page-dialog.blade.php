<?php
/* @var \Manadev\Ui\Dialogs\Views\PageDialog $view */
?>
<main class="page-dialog {{ $view->modifier }}" id="{{ $view->id_ }}">
    @include($view->content)
</main>
{!! $view->view_model_script !!}