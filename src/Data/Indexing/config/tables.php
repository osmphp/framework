<?php

use Osm\Data\Indexing\Indexers;

return [
    'indexers' => [
        'class' => Indexers::class,
        'title' => osm_t("Indexers"),
        'columns' => [
            'id' => ['title' => osm_t("ID")],
            'target' => ['title' => osm_t("Target")],
            'source' => ['title' => osm_t("Source")],
            'events' => ['title' => osm_t("Events")],
            'columns' => ['title' => osm_t("Columns")],
            'requires_partial_reindex' => ['title' => osm_t("Requires Partial Reindex")],
            'requires_full_reindex' => ['title' => osm_t("Requires Full Reindex")],
        ],
    ],
];
