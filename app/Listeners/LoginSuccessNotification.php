<?php

namespace App\Listeners;

use Filament\Facades\Filament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use JeffGreco13\FilamentBreezy\Events\LoginSuccess;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\ActivityLogStatus;

class LoginSuccessNotification
{
    /**
     * Create the event listener.
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Handle the event.
     */
    public function handle(LoginSuccess $event): void
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
