<?php
/* @var \Osm\Ui\Images\Views\Image $view */
?>
@if (!$view->file_)
    <img {!! $view->attributes_ !!}>
@else
    <img {!! $view->attributes_ !!}
        @if ($view->srcset) srcset="{{ $view->srcset }}" @endif
        @if ($view->eager)
            src="{{ $view->src }}"
        @else
            data-src="{{ $view->src }}"
        @endif
    >
@endif

