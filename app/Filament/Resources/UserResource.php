<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = "Authentication";

    protected static ?int $navigationSort = -1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                    ->collection('avatars')
                    ->avatar()
                    ->inlineLabel(false)
                    ->hidden(function (Page $livewire) {
                        return !($livewire instanceof ViewUser);
                    }),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($component, $get, $livewire, $model, $record, $set, $state) => $record === null)
                    ->hidden(function (Page $livewire) {
                        return $livewire instanceof ViewUser;
                    }),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->minItems(1)
                    ->default([2])
                    ->preload(true)
                    ->label('Roles')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\ViewColumn::make('name')
                //     ->view('filament.columns.image-column-with-title'),
                Tables\Columns\ViewColumn::make('name')
                    ->view('filament.columns.image-column-with-title')
                    ->searchable(['name', 'email']),
                Tables\Columns\TagsColumn::make('roles.name')
                    ->getStateUsing(function (User $record) {
                        // dd($);
                        return $record->roles->map(function ($data) {
                            return Str::headline($data->name);
                        })->toArray();
                    }),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->boolean()
                    ->default(false)
                    ->label('Verified'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Joined'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Last Update'),
                Tables\Columns\TextColumn::make('last_login_at')->label('Last Login')->dateTime()->since()
            ])
            // ->reorderable()
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->multiple()
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
