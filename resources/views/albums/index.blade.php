@extends('layouts.default')

@section('head')

	<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

	<script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>
	<script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>

@stop

@section('content')
	@if (Session::get('message'))
		<div class="container">
			<div class="twelve columns">
				<div class="message">{{ Session::get('message') }}</div>
			</div>
		</div>
	@endif
	<!--   HEADER DO USUÁRIO   -->
	<div class="container">
		<div id="user_header" class="twelve columns">
			<div class="info">
				<h1> Meus álbuns </h1>
				<a href="{{ URL::to('/albums/create') }}" title="Criar álbum">
					<!-- <img src="{{ URL::to('/img/plus.png') }}" width="10" height="10"> -->
					 Criar álbum
				</a>
			</div>
			<div class="count">Total de álbuns: {{ $albums->count() }}</div>
		</div>
	</div>
	@if ($albums->count() > 0)
	<div id="user_gallery">
		<div class="wrap">
			<div id="panel" class="stripe">
				@foreach($albums as $album)
					<div class="item h2">
						<div class="layer" data-depth="0.2">
							<a href='{{ URL::to("/albums/{$album->id}") }}'>
								@if(isset($album->cover_id))
									<img src='{{ URL::to("/arquigrafia-images/{$album->cover_id}_micro.jpg") }}'
										data-src='{{ URL::to("/arquigrafia-images/{$album->cover_id}_home.jpg") }}'	title="{{ $album->title }}">
								@else
									<div class="no_cover">
										<p>Álbum sem capa</p>
									</div>
								@endif
							</a>
							<div class="item-title">
								<p>{{ $album->title . ' (' . $album->photos->count() . ')' }}</p>
								<a id="title_delete_button" class="title_delete album" href="{{ URL::to('/albums/' . $album->id) }}" title="Excluir álbum"></a>
								<a id="title_edit_button" href="{{ URL::to('/albums/' . $album->id . '/edit')}}" title="Editar álbum"></a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
			<div class="panel-back"></div>
			<div class="panel-next"></div>
		</div>
	</div>
	<div class="container">
		<div class="twelve columns">
		</div>
	</div>
	@else
		<div class="container">
			<div class="twelve columns">
				<p> 
					Você ainda não possui nenhum álbum. Crie um {{ link_to('/albums/create', 'aqui') }}
				</p>
			</div>
		</div>
	@endif
	<!--   MODAL   -->
	<div id="mask"></div>
	<div id="confirmation_window" class="window">
		<div id="registration_delete">
			<p></p>
			{{ Form::open(array('url' => '', 'method' => 'delete')) }}
				<div id="registration_buttons">
            	<input type="submit" class="btn" value="Confirmar" />
					<a class="btn close" href="#" >Cancelar</a>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	<!--   FIM - MODAL   -->	
@stop