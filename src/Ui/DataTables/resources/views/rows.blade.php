<?php
/* @var \Osm\Ui\DataTables\Views\DataTable $view */

use Osm\Ui\DataTables\Columns\Column;

?>
@foreach ($view->data->items as $item)
    <?php /* @var object $item */ $view->item = $item; ?>
        <li class="data-table__row -id-{{ $item->id }}">
            @foreach ($view->columns_ as $column)
                <?php /* @var Column $column */ $view->column = $column; ?>
                @if ($url = $view->getCellUrl())
                    <a class="data-table__cell -col-{{ $column->name }} -type-{{$column->type}} {{ $column->modifier }}"
                        href="{{ $url }}">
                        @include ($column->cell_template, ['view' => $view])
                    </a>
                @else
                    <div class="data-table__cell -col-{{ $column->name }} -type-{{$column->type}} {{ $column->modifier }}">
                        @include ($column->cell_template, ['view' => $view])
                    </div>
                @endif
            @endforeach
        </li>
@endforeach
