<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestUserLogin extends BaseWidget
{

    protected function getTableHeading(): string | Htmlable | Closure | null
    {
        return 'User Login';
    }

    protected function getTableQuery(): Builder
    {
        return User::orderBy('last_login_at', 'desc')->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('name')
                ->label('Name'),
            Tables\Columns\TextColumn::make('last_login_at')
                ->label('Logged at')
                ->since(),
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return [
            'default' => 'full',
            'lg' => 2
        ];
    }
}