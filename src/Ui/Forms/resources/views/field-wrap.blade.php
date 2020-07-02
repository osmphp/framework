<?php
/* @var \Osm\Ui\Forms\Views\Field $view */
?>
<div id="{{ $view->id_ }}" class="field
    {{ $view->on_color_ }} {{ $view->color_ }}
    {{ $view->type ? "-{$view->type}" : '' }}"
    {!! $view->model('field') !!}
>
    <div class="field__body {{ $view->body_on_color_ }} {{ $view->body_color_ }}">
        @if ($view->title)
            <label class="field__title"
                for="{{ $view->id_ }}__value">{{ $view->title }}</label>
        @endif
        @include ($view->field_template, ['view' => $view])
    </div>

    <div class="field__error"></div>
    @if ($view->comment)
        <div class="field__comment">{{ $view->comment }}</div>
    @endif
</div>
