<?php
/* @var \Manadev\Ui\Dialogs\Views\ModalDialog $view */
?>
<div class="modal-dialog {{$view->modifier}}" id="{{ $view->id_ }}" style="width: {{ $view->width }}px; height: {{ $view->height }}px;">
    @if ($view->header)
        <header class="modal-dialog__header">
            <h6>{{ $view->header }}</h6>
        </header>
    @endif

    <div class="modal-dialog__body">
        @foreach ($view->views_ as $child)
            @include ($child)
        @endforeach
    </div>

    @if ($view->footer)
        <footer class="modal-dialog__footer">
            @include($view->footer)
        </footer>
    @endif
</div>
