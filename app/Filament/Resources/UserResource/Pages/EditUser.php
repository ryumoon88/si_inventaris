<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['password'] === null) {
            unset($data['password']);
        }
        return $data;
    }

    // protected function getForms(): array
    // {
    //     return [
    //         'form' => $this->makeForm()
    //             ->context('edit')
    //             ->model($this->getRecord())
    //             ->statePath('data')
    //             ->inlineLabel(
    //                 config('filament.layout.forms.have_inline_labels'),
    //             )
    //             ->schema([
    //                 Forms\Components\TextInput::make('name')
    //                     ->required()
    //                     ->maxLength(255),
    //                 Forms\Components\TextInput::make('email')
    //                     ->email()
    //                     ->required()
    //                     ->requiredIf('data.email', 'record.email')
    //                     ->maxLength(255),
    //                 Forms\Components\TextInput::make('password')
    //                     ->password()
    //                     ->required()
    //                 // ->filled()
    //             ]),
    //     ];
    // }
}