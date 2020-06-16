<?php
/* @var \Osm\Ui\Buttons\Views\UploadButton $view */

?>
<button type="button" id="{{ $view->id_ }}" class="button -upload
        {{ $view->on_color_ }} {{ $view->color_ }}
        @if ($view->disabled) -disabled @endif
        @if ($view->outlined) -outlined @endif"
        {!! $view->model('button') !!}>

    @if ($view->icon)
        <i class="button__icon icon {{$view->icon}}"></i>
    @endif
    {{ $view->title }}
    <input type="file" class="button__file-input"
        @if ($view->accept) accept="{{ $view->accept }}" @endif
        @if ($view->multi_select) multiple @endif
    >
</button>
