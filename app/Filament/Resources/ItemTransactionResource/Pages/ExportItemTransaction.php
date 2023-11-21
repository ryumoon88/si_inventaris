<?php

namespace App\Filament\Resources\ItemTransactionResource\Pages;

use App\Exports\ItemTransactionsExport;
use App\Filament\Resources\ItemTransactionResource;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Concerns\HasFormComponentActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Contracts\HasFormActions;
use Filament\Resources\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExportItemTransaction extends Page implements HasForms
{
    use InteractsWithForms, HasFormComponentActions;

    protected static string $resource = ItemTransactionResource::class;

    protected static string $view = 'filament.resources.item-transaction-resource.pages.export-item-transaction';

    public $formData = [
        'date_start' => null,
        'date_end' => null,
    ];

    protected function getFormSchema(): array
    {
        return [
            Grid::make(12)
                ->schema([
                    DatePicker::make('date_start')
                        ->maxDate(CarbonImmutable::yesterday()->startOfDay())
                        ->columnSpan(5)
                        ->reactive(),
                    DatePicker::make('date_end')
                        ->disabled(function ($get) {
                            return $get('date_start') == null;
                        })
                        ->minDate(function ($get) {
                            return CarbonImmutable::parse($get('date_start'));
                        })
                        ->maxDate(CarbonImmutable::yesterday()->endOfDay())
                        ->columnSpan(5),
                ])->statePath('formData')
        ];
    }

    public function submit()
    {
        // return Excel::download(new ItemTransactionsExport, 'transactions-' . now() . '.xlsx');
    }

    // protected function getActions(): array
    // {
    //     return [
    //         Action::make('export')
    //             ->action(function ($get) {
    //                 dd($get);
    //             })
    //     ];
    // }

    // public function getCachedFormAction(string $name): ?Action
    // {
    //     return Action::make('export');
    // }
}