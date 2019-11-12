<?php

use Osm\Framework\Gulp\Commands\ConfigGulp;
use Osm\Framework\Gulp\Commands\NotifyDataChanged;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

return [
    'notify:data-changed' => [
        'description' => osm_t("Processes data file change notifications"),
        'class' => NotifyDataChanged::class,
        'arguments' => [
            'path' => [
                'type' => InputArgument::IS_ARRAY,
                'description' => osm_t("Modified/deleted path"),
            ],
        ],
        'options' => [
            'filelist' => [
                'type' => InputOption::VALUE_OPTIONAL,
                'description' => osm_t("Name of file containing list of modified/deleted paths"),
            ],
        ],
    ],

    'config:gulp' => [
        'description' => osm_t("Updates Gulp configuration"),
        'class' => ConfigGulp::class,
    ],
];