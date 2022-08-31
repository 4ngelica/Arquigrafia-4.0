@extends('/layouts.default')

@section('head')

<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

<!-- ISOTOPE -->
<script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>

<script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>


<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/checkbox.css" />
@stop

@section('content')

	@if (Session::get('message'))
      	<div class="container">
        	<div class="twelve columns">
          		<div class="message">{!! Session::get('message') !!}</div>
        	</div>
        </div>
    @endif

		<?php //include "includes/header.php"; ?>

		<!--   HEADER DO USUÁRIO   -->
		<div class="container">
	      <div id="user_header" class="twelve columns">

             <!-- Avatar with edit profile -->
			<?php if (Auth::check() && Session::get('institutionId') == $institution->id) { ?>
			  <a href= '{{"/institutions/" . $institution->id . "/edit" }}' title="Editar perfil" >
				<?php if ($institution->photo != "") { ?>
            	 <img class="avatar" src="{{ asset($institution->photo) }}" class="user_photo_thumbnail"/>
          		<?php } else { ?>
            	 <img class="avatar" src="{{ asset("img/avatar-perfil.png") }}" width="60" height="60" class="user_photo_thumbnail"/>
          		<?php } ?>
          	  </a>
            <?php }else{ ?>
            		<?php if ($institution->photo != "") { ?>
            	 		<img class="avatar" src="{{ asset($institution->photo) }}" class="user_photo_thumbnail"/>
          			<?php } else { ?>
            			<img class="avatar" src="{{ asset("img/avatar-institution.png") }}" width="60" height="60" class="user_photo_thumbnail"/>
          			<?php } ?>
            <?php } ?>
	        <div class="info">

	          <h1>{{ $institution->name}} </h1>

			 @if ( !empty($institution->city) )
				<p>{{ $institution->city }}</p>
			  @endif

			@if (Auth::check() && Session::get('institutionId') != $institution->id)
    			@if (!empty($follow) && $follow == true )
	    			<a href="{{ URL::to("/friends/follow/" . $institution->id) }}" id="single_view_contact_add">Seguir</a><br />
 				@else
            		<div id="unfollow-button">
					    <a href="{{ URL::to("/friends/unfollow/" . $institution->id) }}">
         					<p class="label success new-label"><span>Seguindo</span></p>
    					</a>
					</div>
 				@endif
 			@elseif (Auth::check() && Session::get('institutionId') == $institution->id)
 				<a href="{{ URL::to("/institutions/" . $institution->id . "/edit") }}" id="single_view_contact_add" title="Edite o seu perfil">Editar perfil</a><br />
			@endif

	        </div>
	      	<div class="count">Imagens compartilhadas ({{ count($photos) }})</div>
	      </div>
	    </div>

    <!-- GALERIA DO USUÁRIO -->
    <div id="user_gallery">

    	@if($photos->count() > 0)
	      <div class="wrap">
   	   		@include('includes.panel-stripe')
      	</div>
      @else
      	<div class="container row">
      		<div class="six columns">
	      		<p>
	      			@if (Auth::check() && Session::get('institutionId') == $institution->id)
	      				Você ainda não possui imagens no seu perfil. Faça o upload de uma imagem
	      				<a href="{{ URL::to('/photos/update/Institutional') }}">aqui</a>
	      			@else
	      				Não possui imagens.
	      			@endif
	      		</p>
      		</div>
      	</div>
      @endif

    </div>

    <br>
    <br>

    <!-- USUÁRIO -->
    <div class="container row">
    	<div class="four columns">
      	<hgroup class="profile_block_title">
        	<h3><i class="profile"></i>Perfil</h3> &nbsp; &nbsp;
        	<?php if (Auth::check() && Session::get('institutionId') == $institution->id) { ?>
        	<a href= '{{"/institutions/" . $institution->id . "/edit" }}' title="Editar perfil" >
        		<img src="{{ asset("img/edit.png") }}" width="16" height="16" />
        	</a>
        	<?php } ?>
        </hgroup>
      	<ul>
			@if ( !empty($institution->name) )
				<li><strong>Nome completo: </strong> {{ $institution->name}}</li>
			@endif
        </ul>
        <br>
        <ul>
			@if ( !empty($institution->email)) <!--&& $institution->visibleEmail == 'yes')-->
				<li><strong>E-mail: </strong>{{ $institution->email }}</li>
			@endif
			@if ( !empty($institution->country) )
				<li><strong>País: </strong>{{ $institution->country }}</li>
			@endif
			@if ( !empty($institution->state) )
				<li><strong>Estado: </strong>{{ $institution->state }}</li>
			@endif
			@if ( !empty($institution->city) )
				<li><strong>Cidade: </strong>{{ $institution->city }}</li>
			@endif

			@if ( !empty($institution->site) )
				<li><strong>Site pessoal: </strong> <a href="{{ $institution->site }}">{{ $institution->site }}</a></li>
			@endif
			</br>

        </ul>
      </div>

      <div class="four columns">
      	<hgroup class="profile_block_title">
        	<h3><i class="follow"></i>Seguindo <!--(institution->following->count())--></h3>

   	 		</hgroup>
        <!--   BOX - AMIGOS   -->
    		<div class="profile_box">
				box amigos
			</div>

      </div>

      <div class="four columns">
      	<hgroup class="profile_block_title">
          <h3><i class="follow"></i>Seguidores <!--(institution->followers->count())--></h3>

        </hgroup>
    		<!--   BOX - AMIGOS   -->
		<div class="profile_box">
          <!--   LINHA - FOTOS - AMIGOS   -->
          <!--   FOTO - AMIGO   -->
          box amigos


		</div>

      </div>

    </div>

    	    <!-- MEUS ALBUNS -->
	<div class="container">

			<div class="twelve columns albums">
				<hgroup class="profile_block_title">
					<h3><i class="photos"></i>
						@if (Auth::check() && Session::get('institutionId') == $institution->id)
							Meus álbuns
						@else
							Álbuns
						@endif
					</h3>
				</hgroup>
				<?php //$albums = $institution->albums; ?>

				<div class="profile_box">
					albuns
				</div>

			</div>

	</div>

	<br>
	<br>
	<!-- MINHAS AVALIAÇÕES -->
	<div class="container">
		<div class="twelve columns albums">
			<hgroup class="profile_block_title">
				<h3><img src="{{ asset("img/evaluate.png") }}" width="16" height="16"/>
					@if (Auth::check() && Session::get('institutionId') == $institution->id)
						<!--Minhas imagens avaliadas-->
						Minhas imagens interpretadas
					@else
						<!--Imagens avaliadas-->
						Imagens interpretadas
					@endif
				</h3>
			</hgroup>

			<div class="profile_box">
				photos avaliadas
			</div>
		</div>
	</div>


		<!--   MODAL   -->
	<div id="mask"></div>
	<div id="form_window" class="form window">
		<a class="close" href="#" title="FECHAR">Fechar</a>
		<div id="registration"></div>
	</div>
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

@stop
