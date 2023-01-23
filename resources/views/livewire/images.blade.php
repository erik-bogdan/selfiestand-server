<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 " style="row-gap: 2em; column-gap: 2em">
    @foreach ($images as $key => $image)
        <div class="relative image">
        <div class="absolute no-sign">{{$images->count() - $key}}</div>
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
    @endforeach
    </div>
</div>
