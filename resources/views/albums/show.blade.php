@extends('layouts.default')

@section('head')

<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

<script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>

<script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/checkbox.css" />
@stop

@section('content')
	<!--   HEADER DO USUÁRIO   -->
	<div class="container">
		<div id="user_header" class="twelve columns">
			<div class="info">
				<h1>
					Álbum {{ $album->title }}
				</h1>
			</div>
			<div class="count">Fotos no álbum ({{ $photos->count() }})</div>
		</div>
	</div>
	
	<!-- GALERIA DO USUÁRIO -->
	@if($photos->count() > 0)
		<div id="user_gallery">
			<div class="wrap">
				@include("includes.panel-stripe")
			</div>
		</div>
	@else
		<div class="container row">
			<div class="twelve columns">
			<p>
				Este álbum ainda não possui imagens.
				@if ($album->user_id == Auth::id())
					Para adicionar suas imagens, clique <a href="{{ URL::to('/albums/' . $album->id . '/edit') }}">aqui</a>
				@endif
			</p>
			</div>
		</div>
	@endif
	</br>
    </br>

	<div class="container row">
		<div class="twelve columns albums">
			<hgroup class="profile_block_title">
				<h3><i class="info"></i>Informações</h3>
				@if ( Auth::check() && (($album->user_id == Auth::id() && $institutionlogged == false && $album->institution_id == NULL ) || ($institutionlogged == true && $album->institution_id == Session::get('institutionId') && $album->institution_id != null)  ) )
					<a id="delete_button" class="album" href="{{ URL::to('/albums/' . $album->id) }}" title="Excluir álbum"></a>
					<a id="edit_button" href="{{ URL::to('/albums/' . $album->id . '/edit')}}" title="Editar álbum"></a>
				@endif
				
			</hgroup>
			<ul>
				@if ( Session::has('institutionId') )
					<li><strong>Autor: </strong> <a href="{{ URL::to('/institutions/' . $user->id) }}">{{ $user->name }}</a></li>
				@else
					<li><strong>Autor: </strong> <a href="{{ URL::to('/users/' . $user->id) }}">{{ $user->name }}</a></li>
				@endif
				<li><strong>Título: </strong> {{ $album->title }} </li>
				<br>
				@if ( !empty($album->description) )
					<li><strong>Descrição: </strong> {{ $album->description }} </li>
				@endif
			</ul>
		</div>
	</div>
	
	<!-- OUTROS ALBUNS -->
	@if ($other_albums->count() > 0)
	<div class="container">
		<div class="twelve columns albums">
			<hgroup class="profile_block_title">
				<h3><i class="photos"></i> Outros álbuns
					@if(Session::get('institutionId')!= $album->institution_id)
						de {{ $user->name }}
					@elseif(($album->user_id != Auth::id()))
						de {{ $user->name }}			
					@endif
				</h3>
			</hgroup>
			<div class="profile_box">
				@foreach($other_albums as $other_album)
					<div class="gallery_box">
						<a href="{{ URL::to("/albums/" . $other_album->id) }}" title="{{ $other_album->title }}">
							@if (isset($other_album->cover_id))
								<img src="{{ URL::to("/arquigrafia-images/" . $other_album->cover_id . "_home.jpg") }}" class="gallery_photo" />
							@else
								<div class="gallery_photo">
									<div class="no_cover">
										<p> Álbum sem capa </p>
									</div>
								</div>
							@endif
						</a>
						<a href="{{ URL::to("/albums/" . $other_album->id) }}" class="name">{{ $other_album->title . ' ' . '(' . $other_album->photos->count() . ')' }}</a>
					</div>
				@endforeach
			</div>
		</div>
	</div>
	@endif
	<!--   MODAL   -->
	<div id="mask"></div>
	<div id="form_window" class="form window">
		<a class="close" href="#" title="FECHAR">Fechar</a>
		<div id="registration"></div>
	</div>
	<div id="confirmation_window" class="window">
		<div id="registration_delete">

			<p></p>
			{{ Form::open(array('url' => '/albums/' . $album->id, 'method' => 'delete')) }}
				<div id="registration_buttons">
            	<input type="submit" class="btn" value="Confirmar" />
					<a class="btn close" href="#" >Cancelar</a>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	<!--   FIM - MODAL   -->	
@stop