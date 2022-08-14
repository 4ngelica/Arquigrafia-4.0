@extends('layouts.default')

@section('head')

	<title>Arquigrafia - Seu universo de imagens de arquitetura</title>
	<script src="{{ URL::to('/js/albums-covers.js') }}"></script>
	<script src="{{ URL::to('/js/album-add-photos.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/checkbox.css" />	
	<script>
		var currentPage = 1;
		var rmCurrentPage = 1;
		var maxPage = {{ $maxPage }};
		var rmMaxPage = {{ $rmMaxPage }};
		var loadedPages = [1];
		var rmLoadedPages = [1];
		var url = '{{ $url }}';
		var rmUrl = '{{ $rmUrl }}';
		var coverPage = 1;
		var maxCoverPage = {{ ceil($album->photos->count() / 48) }};
		var album = {{ $album->id }};
		var covers_counter = 0;
		$(document).ready(function() {
			$("#add-container").hide();
		});
	</script>
@stop

@section('content')
	<div class="container">
				
		<div class="twelve columns">
			<h1>Edição do álbum {{ $album->title }}</h1>
			<p>Edite seu álbum<br>
			<small>* Os campos a seguir são obrigatórios.</small>
			</p>			
		</div>
			
		<div id="registration">
			{{ Form::open(array('url' => 'albums/' . $album->id, 'method' => 'put')) }}
				<div class="five columns row">
					<div class="one column alpha"><p>{{ Form::label('title', 'Título*') }}</p></div>
					<div class="four columns omega">
						<p>{{ Form::text('title', $album->title) }} <br>
							<div class="error">{{ $errors->first('title') }} </div>
						</p>
					</div>
					
					<div class="one column alpha"><p>{{ Form::label('description', 'Descrição') }}</p></div>
					<div class="four columns omega">
						<p>{{ Form::textarea('description', $album->description) }}</p>
					</div>
				</div>
				<div class="three columns row">
					<div class="two columns">
						<p>Capa do álbum</p>
					</div>
					<div class="two columns">
						@if (isset($album->cover_id))
							<img id="cover-img" src="{{ URL::to('/arquigrafia-images/' . $album->cover_id . '_home.jpg') }}">
							{{ Form::hidden('_cover', $album->cover_id, ['id' => '_cover']) }}
						@else
							<img id="cover-img" src="{{ URL::to('/img/registration_no_cover.png') }}">
							{{ Form::hidden('_cover', '', ['id' => '_cover']) }}
						@endif
						
					</div>
					<div id="cover_btn_box" class="four columns">
						@if ($album_photos->count() > 0)
							<a class="cover_btn" href="#" >Selecionar capa</a>
						@else
							<p>Para alterar a capa do álbum, é preciso ter pelo menos uma imagem.</p>
							@endif
					</div>
				</div>
				<?php 
					$photos = $album_photos;
					$type = 'rm';
				?>					
				@if ($photos->count() > 0)
					<div class="twelve columns alpha row">
						<h2>Imagens do álbum
							<a id="toggle-rm" href="#">[-]</a>
						</h2>
						<p class="row"> Deseja remover alguma imagem? </p>
						<div id="rm-container">
							<div class="six columns alpha row">	
								<a href="#" name="modal" id="rm_select_all" class="btn">Selecionar todas desta página</a>       
								<a href="#" name="modal" id="rm_remove_all" class="btn">Retirar seleção desta página</a>
							</div>
							<div class="eleven columns row">
								<div id="rm" class="eleven columns row">
									<img id="rm_loader" class="loader row" src="{{ URL::to('/img/ajax-loader.gif') }}" />
									@include('albums.includes.album-photos')
								</div>
								<div id="rm-buttons" class="eleven columns alpha">
									<a id="rm-less-less" href="#" class="btn less-than"> &lt;&lt; </a>
									<a id="rm-less" href="#" class="btn less-than"> &lt; </a>
									<p>1/{{ $rmMaxPage }}</p>
									<a id="rm-greater" href="#" class="btn greater-than"> &gt; </a>
									<a id="rm-greater-greater" href="#" class="btn greater-than"> &gt;&gt; </a>
								</div>
							</div>
						</div>
					</div>
				@endif

				<?php 
					$photos = $other_photos; 
					$type = 'add';
				?>
				
				@if ($photos->count() > 0)
					<div class="twelve columns alpha row">
						<h2> Imagens disponíveis para adicionar ao álbum
							<a id="toggle-add" href="#">[+]</a>
						</h2>
						<p class="row">Deseja adicionar alguma imagem?</p>
						<div id="add-container">
							<div class="six columns alpha row">	
								<a href="#" name="modal" id="select_all" class="btn">Selecionar todas desta página</a>       
								<a href="#" name="modal" id="remove_all" class="btn">Retirar seleção desta página</a>
							</div>
							<div class="eleven columns row">
								<div id="add" class="eleven columns row">
									<img id="add_loader" class="loader row" src="{{ URL::to('/img/ajax-loader.gif') }}" />
									@include('albums.includes.album-photos')
								</div>
								<div id="add-buttons" class="eleven columns alpha">
									<a id="less-less" href="#" class="btn less-than"> &lt;&lt; </a>
									<a id="less" href="#" class="btn less-than"> &lt; </a>
									<p>1/{{ $maxPage }}</p>
									<a id="greater" href="#" class="btn greater-than"> &gt; </a>
									<a id="greater-greater" href="#" class="btn greater-than"> &gt;&gt; </a>
								</div>
							</div>
						</div>
					</div>
				@endif
				<div class="four columns alpha">
					<p>{{ Form::submit("EDITAR", array('class'=>'btn')) }}</p>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	<div id="mask"></div>
	<div id="form_window" class="form window">
		<a class="close" href="#" title="FECHAR">Fechar</a>
		<div id="covers_registration"></div>
	</div>
@stop