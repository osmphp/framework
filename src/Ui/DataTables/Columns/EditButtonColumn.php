<?php

namespace Manadev\Ui\DataTables\Columns;

class EditButtonColumn extends Column
{
    public $cell_template = 'Manadev_Ui_DataTables.cells.edit_button';

    public function addToSearch() {
        // column doesn't display any data, so select no columns
    }
}