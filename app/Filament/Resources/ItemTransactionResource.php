<?php

namespace App\Filament\Resources;

use App\Exports\ItemTransactionsExport;
use App\Filament\Resources\ItemTransactionResource\Pages;
use App\Filament\Resources\ItemTransactionResource\RelationManagers;
use App\Models\ItemTransaction;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ItemTransactionResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = ItemTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = "Transactions";


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->suffixAction(function (?ItemTransaction $record): ?Forms\Components\Actions\Action {
                        if ($record == null) {
                            return null;
                        }
                        return Forms\Components\Actions\Action::make('view_item')
                            ->icon('heroicon-s-external-link')
                            ->url(route('filament.resources.items.view', ['record' => $record->item]));
                    })
                    ->label('Item'),
                Forms\Components\Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->suffixAction(function (?ItemTransaction $record): ?Forms\Components\Actions\Action {
                        if ($record == null) {
                            return null;
                        }
                        return Forms\Components\Actions\Action::make('view_supplier')
                            ->icon('heroicon-s-external-link')
                            ->url(route('filament.resources.suppliers.view', ['record' => $record->supplier]));
                    })
                    ->label('Supplier'),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\TextInput::make('price_each')
                    ->required()
                    ->mask(function (TextInput\Mask $mask) {
                        return $mask->money(prefix: 'Rp. ', thousandsSeparator: '.', isSigned: true);
                    }),
                Forms\Components\Select::make('status')
                    ->options(['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'])
                    ->default('Pending')
                    ->hiddenOn(['edit', 'create']),
                Forms\Components\Builder\Block::make('approve_detail')
                    ->schema([
                        Forms\Components\DateTimePicker::make('updated_at')
                            ->formatStateUsing(function (?ItemTransaction $record) {
                                if ($record == null || $record->last_status_updater() == null || $record->status == 'Pending') {
                                    return '-';
                                }
                                return $record->last_status_updater()->created_at;
                            })
                            ->label(function (?ItemTransaction $record) {
                                if ($record == null || $record->status == 'Pending') {
                                    return;
                                }
                                return $record->status . ' at';
                            })
                            ->placeholder('-'),
                        Forms\Components\TextInput::make('updater')
                            ->formatStateUsing(function (?ItemTransaction $record) {
                                if ($record == null || $record->last_status_updater() == null || $record->status == 'Pending') {
                                    return '-';
                                }
                                return $record->last_status_updater()->causer?->name;
                            })
                            ->suffixAction(function (?ItemTransaction $record = null, $context): ?Forms\Components\Actions\Action {
                                if ($record == null || $record->last_status_updater() == null) {
                                    return null;
                                }
                                $action = Forms\Components\Actions\Action::make('view_approver')
                                    ->icon('heroicon-s-external-link');

                                if ($record != null) {
                                    $action = $action->url(route('filament.resources.users.view', ['record' => $record]));
                                }

                                return $context == 'view' ? $action : null;
                            })
                            ->label(function (?ItemTransaction $record) {
                                if ($record == null || $record->status == 'Pending') {
                                    return;
                                }
                                return $record->status . ' by';
                            })
                    ])
                    ->hiddenOn(['edit', 'create'])
                // ->hidden(fn (?ItemTransaction $record) => $record?->status == 'Pending'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issuer.name')
                    ->getStateUsing(fn (ItemTransaction $record) => $record->issuer->is(auth()->user()) ? 'You' : $record->issuer->name)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('price_each')
                    ->prefix('Rp. ')
                    ->getStateUsing(fn (ItemTransaction $record) => number_format($record->price_each, thousands_separator: '.')),
                Tables\Columns\TextColumn::make('total')
                    ->prefix('Rp. ')
                    ->getStateUsing(fn (ItemTransaction $record) => number_format($record->price_each * $record->amount, thousands_separator: '.')),
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
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('approve')
                        ->action(function (ItemTransaction $record, Tables\Actions\Action $action) {
                            $record->approve();
                            $action->success();
                        })
                        ->requiresConfirmation()
                        ->modalSubheading('Are you sure you would like approve this transaction?')
                        ->modalButton('Approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->visible(function (ItemTransaction $record) {
                            return Gate::allows('approve', $record) || Gate::allows('forceApprove', $record);
                        })
                        ->successNotificationTitle('Transaction Approved'),
                    Tables\Actions\Action::make('reject')
                        ->action(function (ItemTransaction $record, Tables\Actions\Action $action) {
                            $record->reject();
                            $action->success();
                        })
                        ->requiresConfirmation()
                        ->modalSubheading('Are you sure you would like reject this transaction?')
                        ->modalButton('Reject')
                        ->icon('heroicon-o-x')
                        ->color('danger')
                        ->visible(function (?ItemTransaction $record) {
                            // dd(Gate::allows('forceReject', $record));
                            return Gate::allows('reject', $record) || Gate::allows('forceReject', $record);
                        })
                        ->successNotificationTitle('Transaction Rejected'),
                    Tables\Actions\Action::make('pending')
                        ->action(function (ItemTransaction $record, Tables\Actions\Action $action) {
                            $record->pending();
                            $action->success();
                        })
                        ->requiresConfirmation()
                        ->modalSubheading('Are you sure you would like pending this transaction?')
                        ->modalButton('Pending')
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->visible(function (?ItemTransaction $record) {
                            return Gate::allows('pending', $record) || Gate::allows('forcePending', $record);
                        })
                        ->successNotificationTitle('Transaction Pending'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->appendHeaderActions([
                Tables\Actions\Action::make('export')
                    ->form([
                        Grid::make(12)->schema([
                            DatePicker::make('date_start')
                                ->columnSpan(6)
                                ->maxDate(now()->yesterday()->startOfDay())
                                ->reactive()
                                ->format('Y-m-d')
                                ->required(),
                            DatePicker::make('date_end')
                                ->columnSpan(6)
                                ->disabled(function ($get) {
                                    return $get('date_start') == null;
                                })
                                ->minDate(function ($get) {
                                    return $get('date_start');
                                })
                                ->maxDate(now()->endOfDay())
                                ->format('Y-m-d')
                                ->required()
                        ]),
                    ])
                    ->action(function ($action) {
                        $formData = $action->getFormData();
                        return Excel::download(new ItemTransactionsExport($formData['date_start'], $formData['date_end']), 'transactions-' . now() . '.xlsx');
                    })
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
            'index' => Pages\ListItemTransactions::route('/'),
            'create' => Pages\CreateItemTransaction::route('/create'),
            'export' => Pages\ExportItemTransaction::route('/export'),
            'view' => Pages\ViewItemTransaction::route('/{record}'),
            'edit' => Pages\EditItemTransaction::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'approve',
            'force_approve',
            'reject',
            'force_reject',
            'pending',
            'force_pending',
        ];
    }
}