<?php

return [
    'route_prefix' => 'api/license-plate-metadata',
    'on_value_fail' => [
        'email_to_notify' => env('DEVELOPER_EMAIL', '')
    ]
];
