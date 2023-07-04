<?php

namespace App\Filament\Resources\ItemTransactionResource\Pages;

use App\Filament\Resources\ItemTransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemTransaction extends EditRecord
{
    protected static string $resource = ItemTransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
