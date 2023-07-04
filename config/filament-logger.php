<?php
return [
    'datetime_format' => 'd/m/Y H:i:s',
    'date_format' => 'd/m/Y',

    // 'activity_resource' => \Z3d0X\FilamentLogger\Resources\ActivityResource::class,
    'activity_resource' => \App\Filament\Resources\ActivityResource::class,


    'resources' => [
        'enabled' => true,
        'log_name' => 'Resource',
        'logger' => \App\Loggers\ResourceLogger::class,
        'color' => 'success',
        'exclude' => [
            //App\Filament\Resources\UserResource::class,
        ],
    ],

    'access' => [
        'enabled' => true,
        // 'logger' => \Z3d0X\FilamentLogger\Loggers\AccessLogger::class,
        'logger' => \App\Listeners\LoginSuccessNotification::class,
        'color' => 'danger',
        'log_name' => 'Access',
    ],

    'notifications' => [
        'enabled' => true,
        'logger' => \App\Loggers\NotificationLogger::class,
        'color' => null,
        'log_name' => 'Notification',
    ],

    'models' => [
        'enabled' => true,
        'log_name' => 'Model',
        'color' => 'warning',
        'logger' => \App\Loggers\ModelLogger::class,
        'register' => [
            //App\Models\User::class,
        ],
    ],

    'approvals' => [
        'log_name' => 'Transaction',
        'color' => 'primary',
        'logger' => \App\Loggers\ApprovalLogger::class
    ]
];
