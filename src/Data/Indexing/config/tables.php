<?php

use Manadev\Data\Indexing\Indexers;

return [
    'indexers' => [
        'class' => Indexers::class,
        'title' => m_("Indexers"),
        'columns' => [
            'id' => ['title' => m_("ID")],
            'target' => ['title' => m_("Target")],
            'source' => ['title' => m_("Source")],
            'events' => ['title' => m_("Events")],
            'columns' => ['title' => m_("Columns")],
            'requires_partial_reindex' => ['title' => m_("Requires Partial Reindex")],
            'requires_full_reindex' => ['title' => m_("Requires Full Reindex")],
        ],
    ],
];
