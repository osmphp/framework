<?php

use Osm\Ui\DataTables\Columns\Column;
use Osm\Ui\DataTables\Columns;

return [
    Column::STRING => Columns\StringColumn::class,
    Column::OPTION => Columns\OptionColumn::class,
    Column::EDIT_BUTTON => Columns\EditButtonColumn::class,
];
