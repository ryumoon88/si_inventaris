<?php

namespace App\Providers;

use App\Listeners\LoginSuccessNotification;
use App\Loggers\ApprovalLogger;
use App\Models\ItemTransaction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeffGreco13\FilamentBreezy\Events\LoginSuccess;

class EventServiceProvider extends ServiceProvider
{
    // protected $observers = [
    //     \App\Listeners\Loggers\ResourceLogger::class
    // ];

    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // LoginSuccess::class => [
        //     LoginSuccessNotification::class,
        // ]
    ];

    protected $observers = [
        ItemTransaction::class => [ApprovalLogger::class]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}