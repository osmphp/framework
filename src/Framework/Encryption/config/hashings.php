<?php

use Osm\Framework\Encryption\Hashing;

return [
    'bcrypt' => Hashing\Bcrypt::class,
    'argon2i' => Hashing\Argon2i::class,
];