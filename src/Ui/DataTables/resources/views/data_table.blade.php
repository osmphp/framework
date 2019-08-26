<?php
/* @var \Osm\Ui\DataTables\Views\DataTable $view */

use Osm\Ui\DataTables\Columns\Column;

?>
<div class="data-table {{ $view->modifier }}" id="{{ $view->id_ }}">
    <ul class="data-table__table">
        <li class="data-table__header">
            @foreach ($view->columns_ as $column)
                <?php /* @var Column $column */ $view->column = $column; ?>
                <div class="data-table__column-header -col-{{ $column->name }} -type-{{$column->type}} {{ $column->modifier }}"
                    id="{{$view->id_ . '__' . $column->name}}">
                    {{ $column->title }}
                </div>
            @endforeach
        </li>
        @if (!$view->data->count)
            <li class="data-table__empty-row">{{ $view->not_found_message }}</li>
        @else
            @include ($view->rows_template, ['view' => $view])
        @endif
    </ul>
    <script type="text/template" class="data-table__row-template">
        <li class="data-table__row -placeholder">
            @foreach ($view->columns_ as $column)
                <?php $view->column = $column; ?>
                <div class="data-table__cell -placeholder -col-{{ $column->name }} -type-{{
                    $column->type}} {{ $column->modifier }}">
                </div>
            @endforeach
        </li>

    </script>
</div>
{!! $view->view_model_script !!}
