<?php

namespace Manadev\Data\Tables\Columns;

use Manadev\Framework\Data\CollectionRegistry;

class ColumnTypes extends CollectionRegistry
{
    public $class_ = ColumnType::class;
    public $config = 'table_column_types';
    public $not_found_message = "Column type ':name' not found";
}