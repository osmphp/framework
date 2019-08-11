# Usage #

Option lists are requested from global `$m_app` object:

    $optionList = $m_app->option_lists['yes_no'];

You can retrieve individual option using array syntax (so option value can only be only `int` and `string`):

    $option = $optionList[0];

Option is plain object at the very minimum containing `title` property:

    echo $option->title; // renders 'No'

You can add option titles to collection of objects:

    $collection = collect([
        (object)['active' => 0],
        (object)['active' => 1],
    ]);

    $optionList->addToCollection($collection, 'active', ['title' => 'active__title']);

Or to query, if option list supports that:

    $query = $m_app->db['books'];

    if ($optionList->supports_db_queries) {
        $optionList->addToQuery($query, 'active', ['title' => 'active__title']);
    }
