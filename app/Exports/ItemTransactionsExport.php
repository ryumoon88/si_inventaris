<?php

namespace App\Exports;

use App\Models\ItemTransaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ItemTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return ItemTransaction::all();
    // }

    public $stockMapping = [];

    public $date_start, $date_end;

    public function __construct($date_start, $date_end)
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
    }

    public function map($invoice): array
    {
        $format = numfmt_create('id_ID', NumberFormatter::CURRENCY);

        return [
            $invoice['date'],
            $invoice['item'],
            $invoice['initial'],
            // $invoice['in/out'],
            // numfmt_format_currency($format, $invoice['price'], 'IDR'),
            // numfmt_format_currency($format, $invoice['total'], 'IDR'),
            $invoice['inout'],
            $invoice['current'],
        ];
    }

    public function collection()
    {
        $sum = new Collection();
        $transactions = ItemTransaction::with(['item'])->orderBy('created_at', 'asc')->get();
        $transactions->each(function ($transaction, $index) use ($sum) {
            // if (!Arr::has($this->stockMapping, $transaction->item_id)) {
            //     Arr::set($this->stockMapping, $transaction->item_id, $transaction->amount);
            // } else {
            //     Arr::set($this->stockMapping, $transaction->item_id, Arr::get($this->stockMapping, $transaction->item_id) + ($transaction->amount));
            // }
            $initial = Arr::has($this->stockMapping, $transaction->item_id) ? $this->stockMapping[$transaction->item_id] : 0;
            $inOut = $transaction->amount;
            $current = $initial + $inOut;
            // Arr::set($this->stockMapping, $transaction->item_id, $current);
            $this->stockMapping[$transaction->item_id] = $current;
            $data = [
                'date' => $transaction->created_at,
                'item' => $transaction->item->name . ' #' . $transaction->item->id,
                'initial' => $initial,
                'inout' => $inOut,
                'current' => $current,
            ];
            // if ($index == 0) {
            //     dd($this->stockMapping);
            // }
            $sum->push($data);
        });
        return $sum;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DATETIME,
            // 'D' => NumberFormat::FORMAT_CURRENCY_USD,
            // 'E' => NumberFormat::FORMAT_CURRENCY_USD
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Item',
            'Initial',
            'In/Out',
            'Current',

        ];
    }
}
