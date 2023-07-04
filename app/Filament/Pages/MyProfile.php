<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use JeffGreco13\FilamentBreezy\Pages\MyProfile as BaseProfile;
use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use JeffGreco13\FilamentBreezy\FilamentBreezy;

class MyProfile extends BaseProfile
{
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.my-profile';

    public $old_password;

    // protected static ?string $slug = 'profile';

    // protected static string $view = "filament-breezy::filament.pages.my-profile";

    protected function getUpdateProfileFormSchema(): array
    {
        return [
            Forms\Components\SpatieMediaLibraryFileUpload::make('media')->avatar()->collection('avatars')->model($this->user),
            Forms\Components\TextInput::make('name')
                ->required()
                ->label(__('filament-breezy::default.fields.name')),
            Forms\Components\TextInput::make($this->loginColumn)
                ->required()
                ->email(fn () => $this->loginColumn === 'email')
                ->unique(config('filament-breezy.user_model'), ignorable: $this->user)
                ->label(__('filament-breezy::default.fields.email')),
        ];
    }

    protected function getUpdatePasswordFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('old_password')
                ->password()
                ->currentPassword()
                ->required()
                ->reactive(),
            Forms\Components\TextInput::make("new_password")
                ->label(__('filament-breezy::default.fields.new_password'))
                ->password()
                ->rules(app(FilamentBreezy::class)->getPasswordRules())
                ->required()
                ->different('old_password'),
            Forms\Components\TextInput::make("new_password_confirmation")
                ->label(__('filament-breezy::default.fields.new_password_confirmation'))
                ->password()
                ->same("new_password")
                ->required(),
        ];
    }

    public function updatePassword()
    {
        $state = $this->updatePasswordForm->getState();
        $this->user->update([
            "password" => Hash::make($state["new_password"]),
        ]);
        session()->forget('password_hash_' . config('filament.auth.guard'));
        Filament::auth()->login($this->user);
        $this->notify("success", __('filament-breezy::default.profile.password.notify'));
        $this->reset(["new_password", "new_password_confirmation", 'old_password']);
    }
}