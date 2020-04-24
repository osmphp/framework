<?php
/* @var \Osm\Ui\Forms\Views\DropdownField $view */
?>
<select class="field__value" id="{{ $view->id_ }}__value"
    name="{{$view->prefix}}{{$view->name}}">

    <option value="" @if (!$view->value) selected @endif></option>
    @foreach($view->options_ as $value => $option)
        <option value="{{ $value }}"
            @if ($view->value === $value) selected @endif
            >{{ $option->title }}</option>
    @endforeach
</select>
