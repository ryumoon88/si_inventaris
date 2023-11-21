<?php

namespace App\Filament\Resources\ItemTransactionResource\Pages;

use App\Filament\Resources\ItemTransactionResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListItemTransactions extends ListRecords implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ItemTransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getFormSchema(): array
    {
        return [];
    }
}
