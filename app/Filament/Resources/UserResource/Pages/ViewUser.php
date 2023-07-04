<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Route;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    // protected function getForms(): array
    // {
    //     return [
    //         'form' => $this->makeForm()
    //             ->schema([
    //                 Forms\Components\SpatieMediaLibraryFileUpload::make('media')
    //                     ->collection('avatars')
    //                     ->avatar()
    //                     ->inlineLabel(false),
    //                 // ->loadStateFromRelationships(),
    //                 Forms\Components\TextInput::make('name')
    //                     ->required()
    //                     ->maxLength(255),
    //                 Forms\Components\TextInput::make('email')
    //                     ->email()
    //                     ->required()
    //                     ->maxLength(255),
    //                 Forms\Components\DateTimePicker::make('email_verified_at'),
    //             ])
    //             ->context('view')
    //             ->model($this->getRecord())
    //             ->disabled()
    //             ->statePath('data')
    //             ->inlineLabel(config('filament.layout.forms.have_inline_labels'))
    //     ];

    //     // return [
    //     //     'form' => $this->makeForm()
    //     //         ->context('view')
    //     //         ->disabled()
    //     //         ->model($this->getRecord())
    //     //         ->schema($this->getFormSchema())
    //     //         ->statePath('data')
    //     //         ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
    //     // ];
    // }
}