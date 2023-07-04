<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    use HasPageShield;

    public static function getResource(): string
    {
        return config('filament-logger.activity_resource');
    }
}
