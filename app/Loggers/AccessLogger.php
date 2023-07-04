<?php

namespace App\Loggers;

use Filament\Facades\Filament;
use JeffGreco13\FilamentBreezy\Events\LoginSuccess;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\ActivityLogStatus;

class AccessLogger
{
    /**
     * Log user login
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(LoginSuccess $event)
    {
        $description = Filament::getUserName($event->user) . ' logged in';

        app(ActivityLogger::class)
            ->useLog(config('filament-logger.access.log_name'))
            ->setLogStatus(app(ActivityLogStatus::class))
            ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
            ->event('Login')
            ->log($description);

        app(ActivityLogger::class)
            ->withoutLogs(function () use ($event) {
                $event->user->last_login_at = now();
                $event->user->last_login_ip = request()->getClientIp();
                $event->user->timestamps = false;
                $event->user->save();
            });
    }
}
