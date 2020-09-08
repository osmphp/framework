<?php

return [
    'send_emails_via' => osm_env('SEND_EMAILS_VIA'),
    'smtp_host' => osm_env('SMTP_HOST'),
    'smtp_port' => osm_env('SMTP_PORT'),
    'smtp_user' => osm_env('SMTP_USER'),
    'smtp_password' => osm_env('SMTP_PASSWORD'),
    'smtp_encryption' => osm_env('SMTP_ENCRYPTION'),
];