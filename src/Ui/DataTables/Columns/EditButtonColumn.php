<?php

namespace Osm\Ui\DataTables\Columns;

class EditButtonColumn extends Column
{
    public $cell_template = 'Osm_Ui_DataTables.cells.edit_button';

    public function addToSearch() {
        // column doesn't display any data, so select no columns
    }
}