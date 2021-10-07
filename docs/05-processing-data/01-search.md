# Search

Full-text search and layered navigation is a common feature for e-commerce
applications. It's also used in this blog. Actually, it makes browsing any non-trivial
data better. Under the hood, search and layered navigation
interact with ElasticSearch, or other search engine, and this article describes
how.

{{ toc }}

## meta.list_text

Full-text search and layered navigation is a common feature for e-commerce
applications. It's also used in this blog. Actually, it makes browsing any
non-trivial data better. Under the hood, search and layered navigation interact with ElasticSearch, or other search engine, and this article describes how.

## Configuration

### ElasticSearch

Before using search capabilities, configure what search engine you'll use, and specify its connection settings in `settings.{{ app_name }}.php` (usually, `settings.Osm_App.php`):

    ...
    return (object)[
        ...
        'search' => [
            'driver' => 'elastic',
            'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
            'hosts' => [
                $_ENV['ELASTIC_HOST'] ?? 'localhost:9200',
            ],
            'retries' => 2,
        ],
    ];  
    
The example above refers to the ElasticSearch installed on a local machine. For all the settings, consult [ElasticSearch documentation](https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/configuration.html).

The configuration above uses some environment variables, define them in `.env.{{ app_name }}` (sually, `.env.Osm_App`):

    NAME=osmsoftware
    ...
    SEARCH_INDEX_PREFIX="${NAME}_"

### Algolia

Alternatively, you may use Algolia. 

1. After creating an account on <https://www.algolia.com/>, use the following configuration in `settings.{{ app_name }}.php`:

        ...
        return (object)[
            ...
            'search' => [
                'driver' => 'algolia',
                'index_prefix' => $_ENV['SEARCH_INDEX_PREFIX'],
                'app_id' => $_ENV['ALGOLIA_APP_ID'],
                'admin_api_key' => $_ENV['ALGOLIA_ADMIN_API_KEY'],
            ],
        ]; 
    
2. Assign referenced environment variables in `.env.{{ app_name }}`:

        NAME=osmsoftware
        ...
        SEARCH_INDEX_PREFIX="${NAME}_"
        ALGOLIA_APP_ID=...
        ALGOLIA_ADMIN_API_KEY=... 
        
## Creating Indexes

An index in a search engine is somewhat similar to a database table. First you create it, then you fill it in with data, then you make queries from it. Finally, if it's no longer needed, you drop it. Use the following methods for creating/dropping indexes:

    // create an index
    $this->search->create('posts', function (Blueprint $index) {
        $index->string('title')
            ->searchable();
        $index->string('text')
            ->searchable();
        $index->string('tags')
            ->array()
            ->searchable()
            ->filterable();
        $index->string('series')
            ->searchable()
            ->filterable();
        $index->string('created_at')
            ->sortable();
        $index->int('year')
            ->filterable()
            ->faceted();
        $index->string('month')
            ->filterable()
            ->faceted();
        $index->string('category')
            ->array()
            ->filterable()
            ->faceted();
    });
    
    // check if an index exists
    if ($osm_app->search->exists('posts')) {
        ...
    }
    
    // drop an index
    $this->search->drop('posts');

**Note**. If you are familiar with Laravel, you'll find this syntax familiar. It's on purpose. Osm Framework uses Laravel database API, and the search index API is consistent with that.  

### `id` Field

`id` field is implicitly defined in every index, and internally, it is used as a unique
document identifier. Always provide `id` value in the `insert()`, and use the same value in `update()` and `delete()`.

### Field Types

Use the following field types:

    $index->string('sku');
    $index->int('qty');
    $index->float('price');
    $index->bool('in_stock');

### Field Attributes

You may use the following attributes in the field definitions:

    $index->string('category')
        ->array()       // allow assignning multiple values to the field
        ->filterable()  // allow filtering by the field
        ->faceted()     // allow counting field facets
        ->searchable()  // add the field values into the full-text search index
        ->sortable()    // allow sorting by the field

### Complex Orders

You may define multiple-field orders as follows:

    $index->order('complex', desc: false)
        ->by('price', desc: true)
        ->by('id', true);
    
### Engine-Specific Index Settings

The underlying engines have more features to configure, and with time the described API will cover most of them. If you need those features right now, configure them by adding engine-specific logic:

    // modify ElasticSearch index creation request
    $index->on('elastic:creating', fn($request) => merge($request, [
        'settings' => [
            'index' => [
                'number_of_shards' => 2, 
            ],        
        ],    
    ]);  

    // do things after an ElasticSearch index is created
    $index->on('elastic:created', function() use ($index) {
        $index->search->client->...
    });  

    // modify Algolia index settings
    $index->on('algolia:creating', fn($request) => merge($request, [
        'customRanking' => ['desc(followers)']
    ]);  

    // do things after an Algolia index is created
    $index->on('algolia:created', function() use ($index) {
        $index->index()->...
    });  

Check [`Osm\Framework\ElasticSearch\Blueprint::create()`](https://github.com/osmphp/framework/blob/HEAD/src/ElasticSearch/Blueprint.php) and [`Osm\Framework\AlgoliaSearch\Blueprint::create()`](https://github.com/osmphp/framework/blob/HEAD/src/AlgoliaSearch/Blueprint.php) method implementations in order to better understand how exactly your search engine-specific settings are actually added to the underlying requests. 

See also: 

* <https://www.elastic.co/guide/en/elasticsearch/reference/current/indices-create-index.html>
* <https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/>
* <https://www.algolia.com/doc/api-reference/api-methods/set-settings/>
* <https://www.algolia.com/doc/api-reference/api-methods/>

## Adding Data To A Search Index

Use SQL-like `insert()`, `update()` and `delete()` methods of the index query object to manage data in an index:

    $osm_app->search->index('posts')->insert([
        'id' => 5,
        'title' => 'Hello, world',
    ]);
    
    $osm_app->search->index('posts')->update(5, [
        'title' => 'Hello, world',
    ]);
    
    $osm_app->search->index('products')->delete(5);
    
### Changes Are Not Instant

Search engines don't wait for an operation to actually happen, and instead, they queue it and return control to your code immediately. It means that if query the index just after making changed to it, the changes won't be returned right away.

In most cases, it's a good thing, but not in unit tests. For this reason, consider enforcing the waiting for the end of each operation in the search engine connection settings:   

    // ElasticSearch
    'search' => [
        ...
        'refresh' => true,
    ],

    // Algolia 
    'search' => [
        ...
        'wait' => true,
    ],

However, it will make your code slower, so don't use these flags in production.

## Querying A Search Index

### Search Queries Return IDs

Search index queries return IDs of matching records, so after querying the search index, populate the actual data from the database using the `whereIn()` method:

    // 1. get IDs from the search index
    $ids = $osm_app->search->index('posts')
        ->search('framework search')
        ->ids();
    
    // 2. populate data from the database
    $items = $osm_app->db->table('posts')
        ->whereIn('id', $ids)
        ->get(['id', 'path']);
        
### Searching And Filtering

Use `search()`, `where()`, `or()`, `and()` methods:

    $ids = $osm_app->search->index('posts')
        // request full-text search
        ->search('framework search')

        // term filters
        ->where('year', '=', 2021)
        ->where('category', 'in', ['framework', 'status'])

        // range filters
        ->where('year', '>=', 2000)                
        ->where('year', '<=', 2009)
        
        // multiple range filters
        ->or(fn(Where $clause) => $clause
            ->and(fn(Where $clause) => $clause
                ->where('weight', '>=', 1.0)                
                ->where('weight', '<', 2.0)
            )
            ->and(fn(Where $clause) => $clause
                ->where('weight', '>=', 5.0)                
            )
        )                
        
        // run the query
        ->ids();

### Getting Faceted Counts And Stats

Use `facetBy()` method to request faceted data, and read it from the `facets` property of the resulting collection:

    $result = $osm_app->search->index('products')
        // apply filters
        ...

        // term counts
        ->facetBy('tags')        
        ->facetBy('category_ids')        

        // min and max values
        ->facetBy('price', min: true, max: true)        

        // skip counting                
        ->facetBy('weight', min: true, count: false)        

        // run the query
        ->get();

    $ids = $result->ids;
    $minPrice = $result->facets['price']->min;
    $tagCounts = $result->facets['tags']->counts;
    
### Sorting And Paging

Use `orderBy()`, `offset()` and `limit()` methods:

    $result = $osm_app->search->index('products')
        // apply filters
        ...

        ->orderBy('price', desc: true)
        ->offset(20)
        ->limit(10)
        
        // run the query
        ->ids();

## Troubleshooting

### Checking ElasticSearch Data

Use the following commands to check what exactly is stored in the ElasticSearch:

    # list all indexes
    curl 'localhost:9200/_cat/indices?v'
    
    # dump the index definition into a file
    curl 'localhost:9200/{index}/_mapping?pretty' > ~/es_schema.json
    
    # dump the index into a file
    curl -XPOST 'localhost:9200/{index}/_search?pretty' -H "Content-Type: application/json" -d '{"query": { "match_all": {} }}' > ~/es_data.json

### ElasticSearch Logging

Enable logging all internal ElasticSearch queries and responses in `settings.{{ app_name }}.php` (usually, `settings.Osm_App.php`) file:

    ...
    /* @see \Osm\Framework\Settings\Hints\Settings */
    return (object)[
        ...
    
        /* @see \Osm\Framework\Logs\Hints\LogSettings */
        'logs' => (object)[
            'elastic' => true,
        ],
    ];
