# Defining New Sheet Column Property #

1. To schema, file `vendor/dubysa/components/src/Data/Sheets/migrations/schema/02_sheet_columns.php`.
2. Update column table record hint class, `\Osm\Data\Sheets\Hints\ColumnHint`.
3. Update list of read properties in column reader, `\Osm\Data\Sheets\Columns\Columns::$select_formulas`.
4. Update column class, `\Osm\Data\Sheets\Columns\Column`.
    1. If needed, add default value calculation.
    2. Add fluent method to be used in sheet schema definitions.
    3. Update how column definition is saved in database in `getRegistrationData()`.


