@foreach($photos as $photo)
  <div class="item h1"><div class="layer" data-depth="0.2">
      <a href="{{ URL::to('/arquigrafia-images/' . $photo->id) }}">
      	@if ($photo->type == "video")
      		<img src="{{ $photo->nome_arquivo) }}" 
        	width="600" height="405" title="{{ $photo->name }}">
      	@else
        	<img src="{{ URL::to('/arquigrafia-images/' . $photo->id . '_view.jpg') }}" 
        	width="600" height="405" title="{{ $photo->name }}">
        @endif
      </a>
  </div></div>
@endforeach