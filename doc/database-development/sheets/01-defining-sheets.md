# Defining Sheets #

{{ toc }}

## Configuration ##

Sheets are defined in `config/sheets.php`:

    return [
        `books` => [
            'class' => TableSheet::class,
            'columns' => [

            ],
        ],
    ];

## Table-Based Sheets ##

Use `TableSheet` class for sheets which store data in simple database table. By default, column definitions in `TableSheet` are filled in with columns from underlying table specified in `table` property.

If not specified in `table` property, `TableSheet` is based on a table having sheet's name. In the example above, it is `books`.

Every column has `formula` which is selected when column is added to search result. For automatically filled in columns, formula selects underlying table column. You can add virtual columns and specify formula for them to be used.

Column may also have `option_list` property which also adds option title to search result if search is executed `forDisplay()`.

