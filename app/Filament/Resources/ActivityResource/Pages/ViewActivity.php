<?php

namespace App\Filament\Resources\ActivityResource\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Resources\Pages\ViewRecord;

class ViewActivity extends ViewRecord
{

    public static function getResource(): string
    {
        return config('filament-logger.activity_resource');
    }
}
