<?php

return [
    'hashing_algorithm' => 'bcrypt',
    'hashing_bcrypt_cost' => PASSWORD_BCRYPT_DEFAULT_COST,
    'hashing_argon2_memory_cost' => 1024, // PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
    'hashing_argon2_time_cost' => 2, // PASSWORD_ARGON2_DEFAULT_TIME_COST,
    'hashing_argon2_threads' => 2, // PASSWORD_ARGON2_DEFAULT_THREADS,
];