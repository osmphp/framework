<?php

use Symfony\Component\Console\Input\InputOption;

return [
    'config:nginx' => [
        'description' => osm_t("Adds this project to Nginx configuration. Run with `root` privileges"),
        'class' => Osm\Framework\Nginx\Commands\ConfigNginx::class,
        'options' => [
            'domain' => [
                'type' => InputOption::VALUE_OPTIONAL,
                'description' => osm_t("Project's Web domain. If omitted, project directory name is used."),
            ],
            'fastcgi_pass' => [
                'type' => InputOption::VALUE_OPTIONAL,
                'description' => osm_t("Address of a PHP FastCGI server. If omitted, 'unix:/var/run/php/phpX.Y-fpm.sock', where X.Y - version of PHP CLI used to execute this command."),
            ],
        ],
    ],
];