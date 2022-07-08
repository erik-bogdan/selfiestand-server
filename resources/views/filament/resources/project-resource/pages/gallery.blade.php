<x-filament::page>
<x-tall-interactive::actionables-manager />

<x-tall-interactive::modal
  id="send-email"
  :form="\App\Forms\EmailForm::class"
  title="Kép küldése manuálisan"
  submitWith="Elküld"
  dismissableWith="Bezár"
  dismissable

 />
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 " style="row-gap: 2em; column-gap: 2em">
@foreach ($record->images as $key => $image)
    <div class="relative image">
      <div class="absolute no-sign">{{$key + 1}}</div>
      <div class="image-menu absolute top-0 left-0 w-full h-full">
        <div class="item-option mr-3">
          <a style="cursor:pointer" onclick="Livewire.emit('modal:open', 'send-email', {{$image->id}})"><img src="{{asset('storage/images/icons/new-message.svg')}}" /></a>
        </div>
        <div class="item-option mr-3">
          <a style="cursor:pointer"  onclick="printPage(`{{route('printImage',$image->id)}}`, `{{asset('storage/test.jpg')}}`)"><img src="{{asset('storage/images/icons/print.svg')}}" /></a>
        </div>
        <div class="item-option">
          <a style="cursor:pointer" href="{{route('downloadfile',$image->id)}}" target="_blank"><img src="{{asset('storage/images/icons/save.svg')}}" /></a>
        </div>
      </div>
      <img class="selfie-image" src="{{ asset('storage/' . $image->thumbnail_path) }}" style="width: 100%" loading="lazy"/>
    </div>
@endforeach
</div>

<button onclick="printPage(`{{asset('storage/test.jpg')}}`)" type="button">
    Open Modal
</button>

<script type="text/javascript">
function closePrint () {
  document.body.removeChild(this.__container__);
}

function setPrint () {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}

function printPage (url, sURL) {
  fetch(url)
  .then(response => response.json())
  .then(data => {
    var oHideFrame = document.createElement("iframe");
    oHideFrame.onload = setPrint;
    oHideFrame.style.position = "fixed";
    oHideFrame.style.right = "0";
    oHideFrame.style.bottom = "0";
    oHideFrame.style.width = "0";
    oHideFrame.style.height = "0";
    oHideFrame.style.border = "0";
    oHideFrame.src = data.data;
    document.body.appendChild(oHideFrame);
  });

  
}
</script>
</x-filament::page>
