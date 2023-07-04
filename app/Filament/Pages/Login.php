<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use JeffGreco13\FilamentBreezy\Events\LoginSuccess;
use JeffGreco13\FilamentBreezy\Http\Livewire\Auth\Login as AuthLogin;

class Login extends AuthLogin
{
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.login';

    protected function attemptAuth($data)
    {
        // ->attempt will actually log the person in, then the response sends them to the dashboard. We need to catch the auth, show the code prompt, then log them in.
        if (!Filament::auth()->attempt([
            $this->loginColumn => $data[$this->loginColumn],
            'password' => $data['password'],
        ], $data['remember'])) {
            $this->addError($this->loginColumn, __('filament::login.messages.failed'));

            return null;
        }
        event(new LoginSuccess(Filament::auth()->user()));
        
        return app(LoginResponse::class);
    }
}