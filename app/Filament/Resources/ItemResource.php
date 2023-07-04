<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers\LoanItemsRelationManager;
use App\Filament\Resources\ItemResource\RelationManagers\TransactionsRelationManager;
use App\Models\Item;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Closure;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Sluggable\SlugOptions;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = "Inventory Management";

    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // Forms\Components\FileUpload::make('qr_code')
                        //     ->image()
                        //     ->formatStateUsing(function (?Item $record) {
                        //         $writer = new PngWriter();
                        //         $qrCode = QrCode::create(route('filament.resources.items.view', ['record' => $record]))
                        //             ->setEncoding(new Encoding('UTF-8'))
                        //             ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                        //             ->setSize(300)
                        //             ->setMargin(10)
                        //             ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                        //             ->setForegroundColor(new Color(0, 0, 0))
                        //             ->setBackgroundColor(new Color(255, 255, 255));
                        //         // dd(public_path());
                        //         // $writer->write($qrCode)->saveToFile(storage_path('app\\public\\qrcodes\\') . 'qrcode.png');
                        //         return ['http://inventory.test/public/qrcodes/qrcode.png'];
                        //     })
                        //     ->panelAspectRatio('1:1')
                        //     ->multiple(false)
                        //     ->inlineLabel(false),
                        // Forms\Components\ViewField::make('qr')
                        //     ->view('media-library::image')
                    ]),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive(),
                Forms\Components\Select::make('item_category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('buy_price')
                    ->mask(function (TextInput\Mask $mask) {
                        return $mask->money(prefix: 'Rp. ', thousandsSeparator: '.', isSigned: true);
                    })
                    ->required(),
                Forms\Components\TextInput::make('quantity_in_stock_w_loaned')
                    ->hint('without loaned stocks')
                    ->label('Quantity')
                    ->formatStateUsing(function (?Item $record, string $context) {
                        if ($context != 'edit') {
                            return;
                        }
                        return $record->quantity_in_stock - $record->loan_items()->sum('amount');
                    })
                    ->hiddenOn(['create', 'edit']),
                Forms\Components\TextInput::make('quantity_in_stock')
                    ->hint('with loaned stocks')
                    ->label('Quantity Total')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category.name')
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('buy_price')
                    ->prefix('Rp. ')
                    ->getStateUsing(fn (Item $record) => number_format($record->buy_price, thousands_separator: '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_in_stock')->label('Stock')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->dateTime()
                    ->since('Asia/Jakarta')
                    ->toggleable(),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->multiple()
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
            // ItemsRelationManager::class
            LoanItemsRelationManager::class,
            TransactionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
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