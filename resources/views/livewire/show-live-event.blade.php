<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="px-[20px]">
        <div class="my-[20px]">
        <h1 class="text-2xl font-bold tracking-tight md:text-3xl filament-header-heading">
            Élő Galéria
        </h1>
        </div>
        <div wire:poll>
            @if (!$isLiveEvent)
            <div>Jelenleg nincs élő esemény!</div>            
            @else 
                @if (count($images) === 0)
                <div>Jelenleg még nem készült kép az eseményen!</div>            
                @else
                <livewire:images :images="$images" /> 
                @endif
            @endif


        </div>
    </div>
</div>
