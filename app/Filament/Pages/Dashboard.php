<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ItemResource\Widgets\ItemStatsOverviewWidget;
use App\Filament\Resources\ItemTransactionResource\Widgets\ItemTransactionsChart;
use App\Filament\Resources\ItemTransactionResource\Widgets\ItemTransactionSummary;
use App\Filament\Resources\UserResource\Widgets\LatestUserLogin;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\ItemTransactionsWidget;
use Closure;
use Filament\Pages\Page;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Support\Facades\Route;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public static function getRoutes(): Closure
    {
        return function () {
            Route::get('/', static::class)->name(static::getSlug());
        };
    }

    protected function getWidgets(): array
    {
        return [
            AccountWidget::class,
            ItemStatsOverviewWidget::class,
            ItemTransactionsChart::class,
            LatestUserLogin::class
        ];
    }


    protected function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'md' => 4,
            'lg' => 6
        ];
    }

    protected function getTitle(): string
    {
        return static::$title ?? __('filament::pages/dashboard.title');
    }
}