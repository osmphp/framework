<?php
/* @var \Osm\Ui\Tables\Views\Table $view */

use Osm\Ui\Tables\Columns\Column;

?>
<div class="table {{ $view->modifier }}" id="{{ $view->id_ }}">
    <ul class="table__table">
        <li class="table__header">
            @foreach ($view->columns_ as $column)
                <?php /* @var Column $column */ $view->column = $column; ?>
                <div class="table__column-header -col-{{ $column->name }} -type-{{$column->type}} {{ $column->modifier }}"
                    id="{{$view->id_ . '__' . $column->name}}">
                    {{ $column->title }}
                </div>
            @endforeach
        </li>
        @if (!$view->data->count)
            <li class="table__empty-row">{{ $view->not_found_message }}</li>
        @else
            @include ($view->rows_template, ['view' => $view])
        @endif
    </ul>
    <script type="text/template" class="table__row-template">
        <li class="table__row -placeholder">
            @foreach ($view->columns_ as $column)
                <?php $view->column = $column; ?>
                <div class="table__cell -placeholder -col-{{ $column->name }} -type-{{
                    $column->type}} {{ $column->modifier }}">
                </div>
            @endforeach
        </li>

    </script>
</div>
{!! $view->view_model_script !!}
