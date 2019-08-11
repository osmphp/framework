# Defining Lists #

Add option list definition to `config/option_lists.php`:

    return [
        'book_status' => ['class' => Status::class, 'title' => m_("Book Status")],
    ];

Define option list class in `OptionLists` namespace, override `all()` method. This method should return array ob objects. Each object at the very least should have `title` property:

    class Status extends OptionList
    {
        const BEING_CREATED = 'being_created';

        protected function all() {
            return collect([
                static::BEING_CREATED => (object)['title' => m_("Being Created")],
            ]);
        }
    }
