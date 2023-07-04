<div class="flex gap-4">
    <img class="w-9 h-9 rounded-full" src="{{ Filament::getUserAvatarUrl($getRecord()) }}"
        alt="{{ $getRecord()->email }}" />
    <p class="flex flex-col">
        <span>{{ $getRecord()->name }}</span>
        <span class="text-xs">{{ $getRecord()->email }}</span>
    </p>
</div>
