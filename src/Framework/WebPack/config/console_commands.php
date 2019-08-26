<?php

use Osm\Framework\WebPack\Commands;
use Symfony\Component\Console\Input\InputOption;

return [
    'config:webpack' => [
        'description' => m_("Updates WebPack configuration"),
        'class' => Commands\ConfigWebPack::class,
        'options' => [
            'all' => [
                'type' => InputOption::VALUE_NONE,
                'description' => m_("Target all themes in all areas, not just current ones"),
            ],
        ],
    ],
];