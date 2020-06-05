<?php

use Osm\Framework\Http\Parameters\String_;
use Osm\Framework\Http\Returns;
use Osm\Ui\Forms\Controllers\Web;
use Osm\Framework\Http\Parameters\Int_;

return [
    'POST /forms/upload-image' => [
        'class' => Web::class,
        'method' => 'uploadImage',
        'returns' => Returns::JSON,
        'parameters' => [
            'width' => ['class' => Int_::class, 'required' => true],
            'height' => ['class' => Int_::class, 'required' => true],
            'path' => ['class' => String_::class, 'pattern' => '/^[0-9a-z\\/]+$/'],
        ],
    ],
];