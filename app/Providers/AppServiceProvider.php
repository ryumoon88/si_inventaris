<?php

namespace App\Providers;

use App\Listeners\Loggers\ResourceLogger;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Filament::registerNavigationGroups([
            'Inventory Management' => NavigationGroup::make()->label('Inventory Management'),
            'Transactions' => NavigationGroup::make()->label('Transactions'),
            'Supplier Management' => NavigationGroup::make()->label('Supplier Management'),
            'Loan Management' => NavigationGroup::make()->label('Loan Management'),
            'Authentication' => NavigationGroup::make()->label('Authentication'),
            'Settings' => NavigationGroup::make()->label('Settings'),
            // 'Settings' => NavigationGroup::make()->label(__('nav.setting')),
        ]);

        FilamentShield::configurePermissionIdentifierUsing(
            fn ($resource) => Str::of($resource)
                ->afterLast('Resources\\')
                ->before('Resource')
                ->replace('\\', '')
                ->snake()
                ->replace('_', '::')
        );
    }
}
