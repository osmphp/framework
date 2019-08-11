# Introduction #

{{ page_toc }}

## Concepts ##

*Sheet* is just some tabular data stored somewhere. Examples: products, modules, sites, users. Sheet is not visual concept, it is set of operations for retrieving, inserting, updating and deleting sheet data.

In many aspects, sheet is similar to database table. Main difference is that it is not necessarily stored in database. It may be stored in some array in memory, it may be stored in some external service. And even if sheet is indeed stored in database, it occupies group of database tables, not just one table.   

In user interface, sheet is visualized with: 

* *data table* which shows sheet as a table
* *data form* which shows sheet columns of one or more sheet records as form fields.  

Classes which implement these concepts:

* `\Manadev\Data\Sheets\Sheet`
* `\Manadev\Ui\DataTables\DataTable`

The rest of the article reviews how `users` sheet, data table and data form are defined and interconnected:

    route handlers -> layout views -> data table definition -> sheet definition 
  
## Routes ##

Routes are defined for showing sheet as data table ans as data form as well as routes for AJAX refresh and data modification requests:

    // vendor/dubysa/components/src/App/Users/config/backend/routes.php
    return [
        'GET /users/' => [
            'class' => Backend\Users::class,
            'method' => 'listPage',
        ],
        'GET /users/edit' => [
            'class' => Backend\Users::class,
            'method' => 'editPage',
        ],
    ];

Defined routes are handled in controller class:

    // vendor/dubysa/components/src/App/Users/Controllers/Backend/Users.php
    class Users extends Controller
    {
        public function listPage() {
            return m_layout('user_list');
        }
    
        public function editPage() {
            return m_layout('user_edit');
        }
    }

Controller code specifies that pages showing user data table and data form should render according to instructions specified in layout layers `user_list` and `user_edit`, respectively.
  
## Layers ##

Layers mentioned in controller are defined in asset subdirectory:

    // vendor/dubysa/components/src/App/Users/backend/layers/user_list.php
    return [
        '@include' => 'page',
        '#page' => [
            'title' => m_("Users"),
            'content' => DataTableContainer::new(['title' => m_("Users"), 'data_table' => 'users']),
        ],
    ];

    // vendor/dubysa/components/src/App/Users/backend/layers/user_edit.php
    return [
        '@include' => 'page',
        '#page' => [
            'title' => m_("User"),
            'content' => DataFormContainer::new(['title' => m_("User")]),
        ],
    ];

List page layer instuctions render `DataTableContainer` view, which in turn render `DataTable` view, which renders data table HTML markup as specified in `users` data table definition.
 
## Data Table Definition ##

    // vendor/dubysa/components/src/App/Users/config/backend/data_tables.php
    return [
        'users' => [
            'sheet' => 'users',
            'update_route' => 'GET /users/edit',
            'columns' => [
                'username' => [],
            ],
        ],
    ];

Defined data table is based on `users` sheet in 2 ways: it shows information from `users` sheet and data table column definitions are inherited from `users` sheet columns.

## Sheet Definition ##

Sheet's underlying database table are created and sheet itself is registered for usage in migration script:

    // vendor/dubysa/components/src/App/Users/migrations/schema/01_users.php
    class Users extends Migration
    {
        public function up() {
            $this->sheets->create('users', Sheet::FLAT_TABLE, function(Blueprint $sheet) {
                $sheet->title("Users");
                $sheet->string('name')->title("Name")->unique();
                $sheet->string('username')->title("User Name")->required();
                $sheet->string('password_hash')->title("Password")->required();
            });
        }
    
        public function down() {
            $this->sheets->drop('users');
        }
    } 

Most important part here is definition of columns:  

    $sheet->string('username')->title("User Name")->required();

Here `string` is column type, `'username'` is column name and `->title("User Name")->required()` assigns column attributes, namely that column title is `"User Name"` and that column values are required - can't be empty.  

---

## Sheet Types ##

`config/sheet_types.php` defines how basic sheet operations are handled. Sheet types are described a bit later:

    return [
        Sheet::FLAT_TABLE => ['title' => m_("Flat Table Sheet"), 'sheet_class' => TableSheets\Flat\Sheet::class],
    ];

## Sheet-Specific Operations ##

`config/sheet_classes.php` defines additional operations specific to the sheet. `Users` class is empty but it is a good place for extensions to add user-specific operations into:

    return [
        'users' => Users\Users::class,
    ];

## Column Types ##

`config/sheet_column_types.php` defines available sheet column types and how they are handled:

    return [
        Column::INT_ => ['title' => m_("Integer")],
        Column::INT_ID => ['title' => m_("Integer ID")],
        Column::INT_OPTION => ['title' => m_("Integer Option")],
    
        Column::STRING_ => ['title' => m_("String")],
        Column::STRING_ID => ['title' => m_("String ID")],
        Column::STRING_OPTION => ['title' => m_("String Option")],
        Column::STRING_TRANSLATABLE => ['title' => m_("String (Translatable)")],
    
        Column::BOOL_ => ['title' => m_("Boolean")],
    ];

## Column Attributes ##

`\Manadev\Data\Sheets\Columns\Column` defines available sheet column attributes and API to assign them in migration script:

* `formula($value)`
* `references($value)`
* `on_delete($value)`
* `unique($value = true)`
* `required($value = true)`
* `faceted($value = true)`
* `title($value)`
* `unsigned($value = true)`
* `option_list($value)`
* `data_table_column_type($value)`



### API ###

    