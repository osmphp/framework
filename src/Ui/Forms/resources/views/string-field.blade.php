<?php
/* @var \Osm\Ui\Forms\Views\StringField $view */
?>
<div class="string-field {{ $view->modifier }}" id="{{ $view->id_ }}">
    <div class="string-field__body">
        @if ($view->title)
            <label class="string-field__title" for="{{ $view->id_ }}__value">{{ $view->title }}</label>
        @endif
        <input class="string-field__value" type="{{ $view->type }}" id="{{ $view->id_ }}__value" value="{{ $view->value }}"
            name="{{$view->autocomplete_prefix}}{{$view->name}}" @if ($view->placeholder) placeholder="{{ $view->placeholder }}" @endif
            @if ($view->autocomplete) autocomplete="{{ $view->autocomplete }}" @endif>
    </div>

    <div class="string-field__error"></div>
    @if ($view->comment)
        <div class="string-field__comment">{{ $view->comment }}</div>
    @endif
</div>
{!! $view->view_model_script !!}