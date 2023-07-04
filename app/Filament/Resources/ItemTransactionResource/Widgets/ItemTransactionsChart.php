<?php

namespace App\Filament\Resources\ItemTransactionResource\Widgets;

use App\Models\ItemTransaction;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ItemTransactionsChart extends LineChartWidget
{
    protected static ?string $heading = 'Transaction';

    public ?string $filter = 'week';

    protected function getHeading(): ?string
    {
        return static::$heading . ' | ' . $this->getFilters()[$this->filter];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        // dd($activeFilter);
        $data = Trend::model(ItemTransaction::class);

        switch ($activeFilter) {
            case 'week':
                $data = $data->between(
                    start: now()->subDays(7),
                    end: now()->endOfDay()
                )->perDay();
                break;
            case 'month':
                $data = $data->between(
                    start: now()->subMonth(),
                    end: now()->endOfDay()
                )->perDay();
                break;
            case 'year':
                $data = $data->between(
                    start: now()->subYear(),
                    end: now()->endOfDay()
                )->perMonth();
                break;
        }

        $data = $data->count();
        // dd($data);
        return [
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate)
                ]
            ],
            'labels' => $data->map(function (TrendValue $value) use ($activeFilter) {
                switch ($activeFilter) {
                    case 'week':
                        return Carbon::parseFromLocale($value->date)->dayName;
                    case 'month':
                        return Carbon::parseFromLocale($value->date)->format('M d');
                    case 'year':
                        return Carbon::parseFromLocale($value->date)->format('M Y');
                }
            })
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return [
            'default' => 'full',
            'lg' => 4
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'Last Year',
        ];
    }
}