@extends('layouts.default')

@section('head')
	<title>Arquigrafia - Seu universo de imagens de arquitetura</title>
	
	<script src="{{ URL::to('/js/album-add-photos.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/checkbox.css" />	
	<script>
		var currentPage = 1;
		var maxPage = {{ $maxPage }};
		var loadedPages = [1];
		var url = '{{ $url }}';
		var photos_count = {{ $photos->count() }};
		$(document).ready(function() {
			@if( !isset($image) )
				var first_photo = $('#add_page' + currentPage + ' .add_photo').first();
				first_photo.prop('checked', true);
			@endif

		});

	</script>
@stop

@section('content')

	@if (Session::get('message'))
		<div class="container">
			<div class="twelve columns">
				<div class="message">{{ Session::get('message') }}</div>
			</div>
		</div>
	@endif

	<div class="container">				
		<div class="twelve columns">
			<h1>Novo álbum</h1>
			<p>Crie um novo álbum.<br><br>
			<small>* Os campos a seguir são obrigatórios.</small>
			</p>
		</div>

		<div id="registration">
			{{ Form::open(array('url' => 'albums', 'id' => 'create_album')) }}
				<div class="three columns row">
					<div class="one column alpha"><p>{{ Form::label('title', 'Título*') }}</p></div>
					<div class="two columns omega">
						<p>{{ Form::text('title') }} <br>
							<div class="error">{{ $errors->first('title') }}</div>
						</p>
					</div>
					
					<div class="one column alpha"><p>{{ Form::label('description', 'Descrição') }}</p></div>
					<div class="two columns omega">
						<p>{{ Form::textarea('description') }}</p>
					</div>
				</div>
				@if( isset($image) )
					<div class="twelve columns row">
							<h2>Imagem do álbum</h2>
							<div class="two columns">
								<img src="{{ URL::to('/arquigrafia-images/' . $image->id . '_home.jpg') }}"
									width="124" height="83">
								{{ Form::hidden('photos_add[]', $image->id ) }}
							</div>
					</div>
				@endif
				<div class="twelve columns row">
					<h2>Imagens disponíveis para adicionar ao álbum
						<a id="toggle-add" href="#">[-]</a>
					</h2>
					@if( isset($image) )
						<p class="row">Deseja inserir alguma imagem? </p>
					@else
						<p id="p_add" class="row">Selecione pelo menos uma imagem para o álbum</p>
					@endif
					<div id="add-container">
						<div class="eight columns row">	
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
				<div class="four columns">
					<p>{{ Form::submit("CRIAR", array('class'=>'btn')) }}</p>
				</div>	
			{{ Form::close() }}
		</div>
	</div>
@stop