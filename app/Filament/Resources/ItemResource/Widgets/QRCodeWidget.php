<?php

namespace App\Filament\Resources\ItemResource\Widgets;

use App\Models\Item;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Filament\Support\Actions\Concerns\CanOpenModal;
use Filament\Widgets\Widget;

class QRCodeWidget extends Widget
{

    protected static string $view = 'filament.resources.item-resource.widgets.q-r-code-widget';

    public ?Item $record = null;

    public function getColumnSpan(): int | string | array
    {
        return 2;
    }

    public function getQRUri()
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create(route('filament.resources.items.view', ['record' => $this->record]))
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        return $writer->write($qrCode)->getDataUri();
    }
}
