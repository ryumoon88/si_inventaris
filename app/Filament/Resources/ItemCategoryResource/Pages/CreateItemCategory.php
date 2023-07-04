<?php

namespace App\Filament\Resources\ItemCategoryResource\Pages;

use App\Filament\Resources\ItemCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateItemCategory extends CreateRecord
{
    protected static string $resource = ItemCategoryResource::class;
}
