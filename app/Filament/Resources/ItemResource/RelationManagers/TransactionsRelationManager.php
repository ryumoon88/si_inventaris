<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use App\Models\ItemTransaction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->url(fn (ItemTransaction $record) => route('filament.resources.suppliers.view', ['record' => $record->supplier])),
                Tables\Columns\TextColumn::make('issuer.name')
                    ->url(fn (ItemTransaction $record) => route('filament.resources.users.view', ['record' => $record->issuer])),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('price_each')
                    ->prefix('Rp. ')
                    ->getStateUsing(fn (ItemTransaction $record) => number_format($record->price_each, thousands_separator: '.')),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn (ItemTransaction $record) => 'Rp. ' . number_format($record->price_each * $record->amount, thousands_separator: '.')),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Approved',
                        'heroicon-o-x-circle' => 'Rejected',
                        'heroicon-o-clock' => 'Pending',

                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Issued at')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (ItemTransaction $record) => route('filament.resources.item-transactions.view', ['record' => $record])),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}