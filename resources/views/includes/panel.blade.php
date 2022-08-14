<?php $i = rand(0,10);?>

@foreach($photos as $photo)

<?php 
  $i++;
  $size = 1; 
  if ($i%7 == 6) $size = 2;
  if ($i%21 == 6) $size = 3;  
?>

<div class="item h<?php echo $size; ?>">

	<div class="layer" data-depth="0.2">

    <a href='{{ URL::to("/photos/{$photo->id}") }}'>
      @if ($photo->type == "video")
        <div class="iconVideo"></div>
      @endif
		<?php 
    if ($photo->type == 'video'){
      $micropath = $photo->nome_arquivo;
      $path = $photo->nome_arquivo;
    }
    else {
		  $micropath = '/arquigrafia-images/'. $photo->id . '_micro.jpg'; 
		  if ($size==1) $path = '/arquigrafia-images/'. $photo->id . '_home.jpg'; 
		  else $path = '/arquigrafia-images/'. $photo->id . '_view.jpg';
    }
		?>
		<img src="{{ asset( $micropath ) }}" data-src="{{ asset( $path ) }}" title="{{ $photo->name }}">
    </a>
    <div class="item-title">
      <p>{{ $photo->name }}</p>
      @if (Auth::check())        
        <a id="title_plus_button" class="title_plus" href="{{ URL::to('/albums/get/list/' . $photo->id)}}" title="Adicionar aos meus álbuns"></a>        
      @endif
      @if (Auth::check() && $photo->institution_id !="" && Session::get('institutionId') == $photo->institution_id)
        <!--<a id="title_delete_button" class="title_delete photo" href="{{ URL::to('/photos/' . $photo->id) }}" title="Excluir imagem"></a>-->

        <a id="title_edit_button" href="{{ URL::to('/photos/' . $photo->id . '/editInstitutional')}}" title="Editar imagem"></a>
      @elseif (Auth::check() && Auth::id() == $photo->user_id && $photo->institution_id == "" &&  !Session::get('institutionId'))
        <a id="title_delete_button" class="title_delete photo" href="{{ URL::to('/photos/' . $photo->id) }}" title="Excluir imagem"></a>
        <a id="title_edit_button" href="{{ URL::to('/photos/' . $photo->id . '/edit')}}" title="Editar imagem"></a>
      @endif
    </div>
	</div>
</div>

@endforeach
