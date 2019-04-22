<?php
/* @var \Manadev\Ui\Inputs\Views\Input $view */
?>
<div class="input {{ $view->modifier }} -large-title" id="{{ $view->id_ }}">
    <div class="input__body">
        @if ($view->title)
            <label class="input__title" for="{{ $view->id_ }}__value">{{ $view->title }}</label>
        @endif
        <input class="input__value" type="{{ $view->type }}" id="{{ $view->id_ }}__value" value="{{ $view->value }}"
            name="{{$view->name}}" @if ($view->placeholder) placeholder="{{ $view->placeholder }}" @endif
            @if ($view->autocomplete) autocomplete="{{ $view->autocomplete }}" @endif>
    </div>

    <div class="input__error"></div>
    <div class="input__comment">{{ $view->comment }}</div>
</div>
{!! $view->view_model_script !!}