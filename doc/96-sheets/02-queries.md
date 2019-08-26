# Queries #

{{ page_toc }}

## API ##

Example:

    $result = $osm_app->sheets['users']
        ->where("username <> ?", 'x1')
        ->facetBy(["active", "account", "account.area"])
        ->get(["username", "active", "account", "account.area AS area"]);
 
Query retrieval "clauses" - methods which configure query:

* `facetBy($formulas, $options = [])`
* `groupBy($formulas, ...$parameters)`
* `orderBy($formulas, ...$parameters)`
* `select($formulas, ...$parameters)`
* `where($formula, ...$parameters)`
* `forDisplay()`

Query methods for actual retrieval:

* `get($formulas, ...$parameters)`
* `first($formulas, ...$parameters)`
* `values($formula, ...$parameters)`
* `value($formula, ...$parameters)`
* `getFacets()`

Query methods for modifying underlying data:

* `insert($values)`
* `update($values)`
* `delete()`

## Facets ##

### Creating Facets ###

*Faceted data* is certain stats about sheet column or formula used for displaying column filters. Examples:

* if column values are options from certain option list (for instance if values are either `Yes` or `No`), faceted data is information about how many `Yes` and `No` options there are in given query result. This information is used to display list of filter options and estimated number of result rows if filter is applied.
* if column values are just numbers, faceted data is minimum and maximum number in given query result. This information is used to show list of filter ranges and estimated number of result rows if filter is applied.
* some columns are not filterable (images) or their column filters don't require any faceted data to work (partial `LIKE` match in string columns, exact match in int columns). 

*Facet* is column name or formula for which faceted data is prepared and retrieved. As faceted data is prepared in advance for all registered facets during indexing, facets should be registered in migration script in one of 2 ways:

    // sheet column may be marked as facet
    $sheet->bool('active')->title("Is Active")->required()->formula("FALSE")->faceted();

    // formula may be marked as facet
    $sheet->faceted("account.area");

*Facet type* specifies how exactly faceted data is prepared for querying. For column marked as facet, facet type is determined from column type in `Osm\Data\Sheets\Columns\FacetClassifier` class. For formula marked as facet, facet type is explicitly specified in call to `$sheet->faceted($formula, $type = Facet::LIST, $options = [])`.  

### Requesting Facets ###

`facetBy($formulas, $options = [])` puts a request for faceted data for specified formula into `query->facets` property which is array of `QueryFacet` objects. 

    $query = $osm_app->sheets['users']
        ->facetBy("active");

Faceted data is actually calculated when one of retrieval methods is invoked:

    $result = $query->get(["id", "active"]);

There is optional second parameter to `facetBy()` which is used to specify applied facet filters and possibly to pass additional instructions on how to prepare faceted data. Options passed through second parameter are then written into properties of `QueryFacet` object:

* `values` - for list facets, array of facet values by which result is additionally filtered.  

