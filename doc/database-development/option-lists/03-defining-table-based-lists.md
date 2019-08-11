# Defining Table-Based Lists #

Add option list definition to `config/option_lists.php`:

    return [
        'book_status' => [
            'class' => TableOptionList::class,
            'title' => m_("Book Status"),
            'table' => 'book_statuses',
        ],
    ];

It is expected that option list values are in table `id` column and titles in `title` column.

If columns are named differently, query is more complex than `SELECT ... FROM table`, or more option properties should be filled in (for instance, SEO URL keys), subclass `TableOptionList` class.