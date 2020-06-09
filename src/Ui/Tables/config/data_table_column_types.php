<?php

use Osm\Ui\Tables\Columns\Column;
use Osm\Ui\Tables\Columns;

return [
    Column::STRING => Columns\StringColumn::class,
    Column::OPTION => Columns\OptionColumn::class,
    Column::EDIT_BUTTON => Columns\EditButtonColumn::class,
];
