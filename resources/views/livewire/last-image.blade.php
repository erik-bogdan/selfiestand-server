<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="px-[20px]">
        <div class="my-[20px]">
        <h1 class="text-2xl font-bold tracking-tight md:text-3xl filament-header-heading">
            Az éppen elkészült kép
        </h1>
        </div>
        <div wire:poll>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 " style="row-gap: 2em; column-gap: 2em">
        @if (!$isLiveEvent)
            <div>Jelenleg nincs élő esemény!</div>            
            @else 
                @if ($image === null)
                <div>Jelenleg még nem készült kép az eseményen!</div>            
                @else
                <div class="relative image ">
                @if (str_contains($image->thumbnail_path, 'gif'))
                <div class="absolute no-sign" style="right: 10px; left: initial">
                    GIF
                </div>
                    @endif
                <div class="image-menu absolute top-0 left-0 w-full h-full">
                
                    <div class="item-option">
                    <a style="cursor:pointer" href="{{route('downloadfile',$image->id)}}" target="_blank"><img src="{{asset('storage/images/icons/save.svg')}}" /></a>
                    </div>
                </div>
                <img class="selfie-image" src="{{ asset('storage/' . $image->thumbnail_path) }}" style="width: 100%" loading="lazy"/>
                </div>
                @endif
            @endif


        </div>
        </div>
    </div>
</div>
