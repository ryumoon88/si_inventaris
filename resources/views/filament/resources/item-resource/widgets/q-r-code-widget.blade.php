<x-filament::widget class="w-full h-full flex">
    <div class="w-full">
        <div class="w-[300px] h-[300px] m-auto" wire:click='mountAction("showQr", {{ $this->getQrUri() }})'
            style="background-image: url('{{ $this->getQRUri() }}'); background-size: cover;">
        </div>
    </div>
</x-filament::widget>
