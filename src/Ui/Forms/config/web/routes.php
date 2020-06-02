<?php

use Osm\Framework\Http\Returns;
use Osm\Ui\Forms\Controllers\Web;

return [
    'POST /forms/upload-image' => [
        'class' => Web::class,
        'method' => 'uploadImage',
        'returns' => Returns::JSON,
    ],
];