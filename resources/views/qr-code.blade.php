@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;

@endphp
<div class="flex justify-center">
    {!! QrCode::size(300)->generate(route('filament.resources.items.view', ['record' => $getRecord()])) !!}
</div>
