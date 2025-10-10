<?php

return [
    'role_structure' => [
        'super_administrator' => [
            'role' => 'v,c,u',
            'user' => 'v,c,u,d',
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'v' => 'view',
        'c' => 'create',
        'u' => 'update',
        'd' => 'delete',
    ],
];

// php artisan db:seed --class=LaratrustSeeder
