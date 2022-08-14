<div class="container">
  <div class="twelveMid columns">
    <div id="add_images" class="" style="display: block;">
      <div id="add" class="twelveMid columns add" >
        @if ( $photos!= null)
          @if ($photos->count() > 0)
          @include('photos.includes.searchResult_include')
        @else
          <p>Não foi encontrada nenhuma imagem sua para sua busca.</p>
        @endif
        @else
          <div class="wrap">
          </div>
        @endif
      </div>
      @if ( $photos!= null)
        <div class="eleven columns block add">
          <div class="eight columns alpha buttons">
            <input type="button" class="btn less less-than" value="&lt;&lt;">
            <input type="button" class="btn less-than" value="&lt;">
            <p>{{$page}} / {{$maxPage}}</p>
            <input type="button" class="btn greater-than" value="&gt;">
            <input type="button" class="btn greater greater-than" value="&gt;&gt;">
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
