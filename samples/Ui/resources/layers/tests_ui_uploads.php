<?php

use Osm\Framework\Views\View;
use Osm\Ui\Buttons\Views\UploadButton;

return [
    '@include' => ['base'],
    '#page.modifier' => '-tests-ui-uploads',
    '#page.items' => [
        'footer' => View::new(['template' => 'Osm_Samples_Ui.footer']),
    ],
    '#content.items' => [
        'upload' => UploadButton::new([
            'title' => osm_t("Upload"),
            'accept' => 'image/*',
            'multi_select' => true,
        ]),
    ],
];