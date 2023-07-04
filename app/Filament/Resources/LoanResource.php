<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Item;
use App\Models\Loan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = "Loan Management";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('loan_start')->default(now())->required(),
                Forms\Components\DatePicker::make('loan_end')->required(),
                Forms\Components\Repeater::make('loan_items')
                    ->relationship('loan_items')
                    ->required()
                    ->schema([
                        Grid::make()
                            ->schema([
                                Forms\Components\Select::make('item_id')
                                    ->relationship('item', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive(),
                                Forms\Components\TextInput::make('amount')
                                    // ->mask(function (TextInput\Mask $mask, $get) {
                                    //     if ($itemId = $get('item_id')) {
                                    //         $item = Item::find($itemId);
                                    //         return $mask->maxValue($item->quantity_in_stock)
                                    //             ->minValue(1)
                                    //             ->numeric();
                                    //     }
                                    // })
                                    ->required()
                                    ->postfix(function ($get) {
                                        if ($itemId = $get('item_id')) {
                                            return Item::find($itemId)->quantity_in_stock . ' available';
                                        }
                                    }),
                            ])
                    ])
                    ->orderable()
                    ->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('loan_start')->date(),
                Tables\Columns\TextColumn::make('loan_end')->date()->placeholder('-')
                    ->description(function (Loan $record) {
                        $diff = Carbon::now()->diffInDays($record->loan_end, false);
                        return Str::of(abs($diff) . Str::plural(' day', $diff) . ' ' . Str::of($diff > 0 ? 'left' : 'passed'));
                    })
                    ->color(fn (Loan $record) => Carbon::now()->diffInDays($record->loan_end, false) > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('returned_at')->placeholder('-')->getStateUsing(function (Loan $record) {
                    return $record->returned_at;
                }),
                // Tables\Columns\Layout\Grid::make([])
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'view' => Pages\ViewLoan::route('/{record}'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
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