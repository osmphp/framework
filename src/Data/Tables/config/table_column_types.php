<?php

use Osm\Data\Formulas\Types;
use Osm\Data\Tables\Columns\Column;

return [
    Column::STRING_ => ['data_type' => Types::STRING_, 'title' => osm_t("String")],
    Column::BOOL_ => ['data_type' => Types::BOOL_, 'title' => osm_t("Yes/No")],
    Column::INT_ => ['data_type' => Types::INT_, 'title' => osm_t("Integer")],
    Column::TEXT => ['data_type' => Types::STRING_, 'title' => osm_t("Text")],
    Column::DATETIME => ['data_type' => Types::DATETIME, 'title' => osm_t("Date/Time")],
    Column::DECIMAL => ['data_type' => Types::FLOAT_, 'title' => osm_t("Decimal")],
];