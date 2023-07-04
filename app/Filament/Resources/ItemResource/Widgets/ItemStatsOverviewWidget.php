<?php

namespace App\Filament\Resources\ItemResource\Widgets;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Loan;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ItemStatsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $totalItems = Item::all()->count();
        $totalCategories = ItemCategory::all()->count();
        $stock = Item::all()->sum('quantity_in_stock');
        $loanedItems = Loan::where('returned_at', null)->get()->count();
        $totalSuppliers = Supplier::all()->count();
        // dd($stock);

        return [
            Card::make('Total Items', $totalItems)
                ->extraAttributes(['class' => 'md:col-span-2 lg:col-span-2']),
            Card::make('Total Categories', $totalCategories)
                ->extraAttributes(['class' => 'md:col-span-2 lg:col-span-2']),
            Card::make('Overall Stock', $stock)->extraAttributes(['class' => 'md:col-span-2 lg:col-span-2']),
            Card::make('Loaned Items', $loanedItems)->extraAttributes(['class' => 'md:col-span-2 lg:col-span-2']),
            Card::make('Total Suppliers', $totalSuppliers)->extraAttributes(['class' => 'md:col-span-2 lg:col-span-2'])
        ];
    }

    public function getColumns(): int
    {
        return 6;
    }

    protected int | string | array $columnSpan = [
        'default' => 'full'
    ];
}