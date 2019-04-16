<?php

return [
    'email' => [
        'pattern' => '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/iu',
        'error_message' => m_("Email expected"),
    ],
    'url_key' => [
        'pattern' => '/^(?:\\p{Ll}|[0-9])(?:\\p{Ll}|[0-9_-])+$/u',
        'error_message' => m_("Expected to start with lowercase letter or digit followed by one or more lowercase letter, digit, hyphen (-) or underscore (_)"),
    ],
];