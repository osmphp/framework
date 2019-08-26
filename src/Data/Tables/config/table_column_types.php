<?php

use Osm\Data\Formulas\Types;
use Osm\Data\Tables\Columns\Column;

return [
    Column::STRING_ => ['data_type' => Types::STRING_, 'title' => m_("String")],
    Column::BOOL_ => ['data_type' => Types::BOOL_, 'title' => m_("Yes/No")],
    Column::INT_ => ['data_type' => Types::INT_, 'title' => m_("Integer")],
    Column::TEXT => ['data_type' => Types::STRING_, 'title' => m_("Text")],
    Column::DATE => ['data_type' => Types::DATETIME, 'title' => m_("Date")],
    Column::DECIMAL => ['data_type' => Types::FLOAT_, 'title' => m_("Decimal")],
];