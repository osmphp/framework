<?php

use Symfony\Component\Console\Input\InputOption;

return [
    'clear:files' => [
        'description' => osm_t("Deletes uploaded files that are not referenced anywhere"),
        'class' => Osm\Data\Files\Commands\ClearFiles::class,
        'options' => [
            'dry-run' => [
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Output orphan files instead of deleting them"),
            ],
            'full' => [
                'type' => InputOption::VALUE_NONE,
                'shortcut' => 'f',
                'description' => osm_t("Check all files, even those not listed in 'files' table"),
            ],
        ],
    ],
];