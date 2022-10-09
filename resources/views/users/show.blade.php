@extends('/layouts.default')

@section('head')

<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

<link rel="stylesheet" href="{{ URL::to("/") }}/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{{ URL::to("/") }}/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
<!-- ISOTOPE -->
<script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>

<script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/dist/profile.bundle.js"></script>
<script type="text/javascript">
  var profileUser = {{ json_encode($user) }}
	var userID = {{$user->id}};
	console.log({{ $userPoints }})
</script>


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

    <!--   HEADER DO USUÁRIO   -->
    <div class="container">
      <div id="user_header" class="twelve columns">
        <!-- Avatar with edit profile -->
        @if (Auth::check() && Auth::user()->id == $user->id)
         <div class="div_avatar_size" >
           <a href= '{{ url("users/" . $user->id . "/edit") }}' title="Editar perfil" >
           @if ($user->photo != "")
             <img class ="class_img_avatar" class="avatar" src="{{ asset($user->photo) }}" class="user_photo_thumbnail"/>
           @else
             <img class="avatar" src="{{ asset("img/avatar-perfil.png") }}"
               width="62" height="62" class="user_photo_thumbnail"/>
           @endif
          </a>
         </div>
        @else
          @if ($user->photo != "")
          	<div class="div_avatar_size" >
            	<img class ="class_img_avatar" class="avatar" src="{{ asset($user->photo) }}"  class="user_photo_thumbnail"/>
            </div>
          @else
            <img class="avatar" src="{{ asset("img/avatar-60.png") }}"
              width="62" height="62" class="user_photo_thumbnail"/>
          @endif
        @endif

        <div class="info" class="position_info">
          <h1>{{ $user->name}} {{ $user->secondName}}</h1>
          <p>
            <a href="{{ URL::to('/leaderboard') }}">
              Ver Quadro de Colaboradores
            </a>
          </p>


			@if (Auth::check() && $user->id != Auth::user()->id && !Session::has('institutionId'))
    			@if (!empty($follow) && $follow == true )
	    			<a href="{{ URL::to("/friends/follow/" . $user->id) }}"
	    				id="single_view_contact_add">Seguir</a><br />
 				@else
            		<div id="unfollow-button" class="label_following">
					    <a href="{{ URL::to("/friends/unfollow/" . $user->id) }}">
         					<p  class="label success new-label"><span>Seguindo</span></p>
    					</a>
					</div>
 				@endif
 				<div id="send_message">
 					<a href="#">
 						<p  class="label new-label"><span>Enviar mensagem</span></p>
 					</a>
 				</div>
 			@elseif (Auth::check() && $user->id == Auth::user()->id && !Session::has('institutionId'))
 				<a href="{{ URL::to("/users/" . $user->id . "/edit") }}" id="single_view_contact_add" title="Edite o seu perfil">Editar perfil</a><br />
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
	      			@if (Auth::check() && $user->id == Auth::user()->id && !Session::has('institutionId'))
	      				Você ainda não possui imagens no seu perfil. Faça o upload de uma imagem
	      				<a href="{{ URL::to('/photos/upload') }}">aqui</a>
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
        	<?php if (Auth::check() && Auth::user()->id == $user->id && !Session::has('institutionId')) { ?>
               	<a href= '{{"/users/" . $user->id . "/edit" }}' title="Editar perfil" >
        		<img src="{{ asset("img/edit.png") }}" width="16" height="16" />
        	</a>
        	<?php } ?>
        </hgroup>
        <ul>
      @if ( !empty($user->name) )
        <li><strong>Nome completo: </strong> {{ $user->name}}</li>
      @endif
      <!--@if ( !empty($user->secondName) )
        <li><strong>Sobrenome:</strong>{{ $user->secondName }}</li>
      @endif-->
        </ul>
        <br>
        <ul>
        	@if ( !empty($user->lastName) )
				<li><strong>Apelido: </strong>{{ $user->lastName }}</li>
			@endif
			@if ( !empty($user->birthday) && $user->visibleBirthday == 'yes')
				<li><strong>Data de nascimento: </strong>{{ date("d/m/Y",strtotime($user->birthday)) }}</li>
			@endif
			@if ( !empty($user->gender) )
				<li><strong>Sexo: </strong>{{ $user->gender == 'female' ? 'Feminino' : 'Masculino' }}</li>
			@endif
			@if ( !empty($user->email) && $user->visibleEmail == 'yes')
				<li><strong>E-mail: </strong>{{ $user->email }}</li>
			@endif
			@if ( !empty($user->country) )
				<li><strong>País: </strong>{{ $user->country }}</li>
			@endif
			@if ( !empty($user->state) )
				<li><strong>Estado: </strong>{{ $user->state }}</li>
			@endif
			@if ( !empty($user->city) )
				<li><strong>Cidade: </strong>{{ $user->city }}</li>
			@endif
			@if ( !empty($user->scholarity) )
				<li><strong>Escolaridade: </strong> {{ $user->scholarity }}</li>
			@endif
			@if ( ( !empty($user->occupation()) ))
        @if( !empty($user->occupation()->institution))
				    <li><strong>Instituição: </strong>{{ $user->occupation->institution}}</li>
			  @endif
      @endif
      @if ( ( !empty($user->occupation()) ))
			   @if (!empty($user->occupation()->occupation) )
				     <li><strong>Ocupação: </strong>{{ $user->occupation->occupation}}</li>
			   @endif
      @endif

			@if ( !empty($user->site) )
				<li><strong>Site pessoal: </strong> <a href="{{ $user->site }}" target="_blank">{{ $user->site }}</a></li>
			@endif
			</br>




        </ul>
      </div>

      <div class="four columns">
        <hgroup class="profile_block_title">
          <h3><i class="follow"></i>Seguindo ({{$user->following->count() + $institutionFollowed->count()}})</h3>
          <!--<a href="#" id="small" class="profile_block_link">Ver todos</a>-->
          </hgroup>
        <!--   BOX - AMIGOS   -->
    		<div class="profile_box">
				@foreach($user->following as $following)
				<div class="gallery_box_inst">
					<a href= "{{ URL::to('/users/' .  $following->id) }}"
					   class="gallery_box_inst" title="{{$following->name}}"	>
					@if ($following->photo != "")
						<img width="40" height="40" class="avatar"
						src="{{ asset($following->photo) }}" class="user_photo_thumbnail"
						class="gallery_box_inst"  />
					</a>
					@else
						<img width="40" height="40" class="avatar"
						src="{{ asset("img/avatar-60.png") }}" class="user_photo_thumbnail"
						class="gallery_box_inst"  />
					</a>
					@endif
					<a href="{{ URL::to("/users/". $following->id) }}" class="name_text_follow">
               			{{ Str::limit(ucfirst($following->name), 5) }}
          			</a>
				</div>
				@endforeach
				@foreach($institutionFollowed as $followingInstitution)
				   <div class="gallery_box_inst">
						<a href= "{{ '/institutions/' .  $followingInstitution->id }}"
						   class="gallery_box_inst" title="{{$followingInstitution->name}}"   >
						@if ($followingInstitution->photo != "")
							<img width="40" height="40" class="avatar"
							src="{{ asset($followingInstitution->photo) }}" class="user_photo_thumbnail"
							class="gallery_box_inst" />
						</a>
						@else
							<img width="40" height="40" class="avatar"
							src="{{ asset("img/avatar-60.png") }}"  class="user_photo_thumbnail"
							class="gallery_box_inst" />
						</a>
						<a href="{{ URL::to('/institutions/'. $followingInstitution->id) }}" class="name_text_follow">
               			{{ Str::limit($followingInstitution->name, 5) }}
               			</a>
						@endif

				   </div>
				@endforeach
			</div>
      </div>

      <div class="four columns">
        <hgroup class="profile_block_title">
          <h3><i class="follow"></i>Seguidores ({{$user->followers->count()}})</h3>
          <!--<a href="#" id="small" class="profile_block_link">Ver todos</a>-->
        </hgroup>
        <!--   BOX - AMIGOS   -->
    <div class="profile_box">
          <!--   LINHA - FOTOS - AMIGOS   -->
          <!--   FOTO - AMIGO   -->

          @foreach($user->followers as $follower)
           <div class="gallery_box_inst">
					<a href= "{{ '/users/' .  $follower->id }}" class="gallery_box_inst" title="{{$follower->name}}">
					 @if ($follower->photo != "")
						<img width="40" height="40" class="avatar" src="{{ asset($follower->photo) }}" class="user_photo_thumbnail" class="gallery_box_inst"/>
					 @else
						<img width="40" height="40" class="avatar" src="{{ asset("img/avatar-60.png") }}" class="user_photo_thumbnail" class="gallery_box_inst"/>
					 @endif
					</a>
					<a href="{{ URL::to("/users/". $follower->id) }}" class="name_text_follow">
               			{{ Str::limit(ucfirst($follower->name), 5) }}
              		</a>
		   </div>
		@endforeach

		</div>

      </div>

    </div>

	<!-- MEUS ALBUNS -->
	<div class="container row">

			<div class="twelve columns albums">
				<hgroup class="profile_block_title">
					<h3><i class="photos"></i>
						@if (Auth::check() && $user->id == Auth::user()->id && !Session::has('institutionId'))
							Meus álbuns
						@else
							Álbuns
						@endif
					</h3>
				</hgroup>
				<?php //$albums = $user->albums; ?>

				<div class="profile_box">
					@if ($albums->count() > 0)
						@foreach($albums as $album)
							<div class="gallery_box">
								<a href="{{ URL::to("/albums/" . $album->id) }}" class="gallery_photo" title="{{ $album->title }}">
									@if (isset($album->cover_id))
										<img src="{{ URL::to("/arquigrafia-images/" . $album->cover_id . "_home.jpg") }}" class="gallery_photo" />
									@else
										<div class="gallery_photo">
											<div class="no_cover">
												<p> Álbum sem capa </p>
											</div>
										</div>
									@endif
								</a>
								<a href="{{ URL::to("/albums/" . $album->id) }}" class="name">
									{{ $album->title . ' ' . '(' . $album->photos->count() . ')' }}
								</a>
								<br />
							</div>
						@endforeach
					@else
						<p>
						@if (Auth::check() && $user->id == Auth::user()->id)
							Você ainda não tem nenhum álbum. Crie um <a href="{{ URL::to('/albums/create') }}">aqui</a>
						@else
							Não possui álbuns.
						@endif
						</p>
					@endif
				</div>

			</div>

	</div>

	<!-- MEUS PONTOS -->
	@include('gamification.user_points')

	<!-- MINHAS CONQUISTAS -->
	@include('gamification.user_badges')

	<br>
	<br>

	<!-- MINHAS AVALIAÇÕES -->
	<div class="container">
		<div class="twelve columns albums">
			<hgroup class="profile_block_title">
				<h3><img src="{{ asset("img/evaluate.png") }}" width="16" height="16"/>
					@if (Auth::check() && $user->id == Auth::user()->id &&!Session::has('institutionId'))
						<!--Minhas imagens avaliadas-->
						Minhas imagens interpretadas
					@else
						<!--Imagens avaliadas-->
						Imagens interpretadas
					@endif
				</h3>
			</hgroup>

			<div class="profile_box">
				@if ($evaluatedPhotos->count() > 0)
					@foreach($evaluatedPhotos as $evaluatedPhoto)
						@if (Auth::check() && $user->id == Auth::user()->id && !Session::has('institutionId'))
							<div class="gallery_box">
								<a href='{{"/evaluations/" . $evaluatedPhoto->id . "/evaluate/" }}' class="gallery_photo" title="{{ $evaluatedPhoto->name }}">
									<img src="{{ URL::to("/arquigrafia-images/" . $evaluatedPhoto->id . "_home.jpg") }}" class="gallery_photo" />
								</a>
								<a href='{{"/evaluations/" . $evaluatedPhoto->id . "/evaluate/" }}' class="name">
									{{ $evaluatedPhoto->name }}
								</a>
								<br />
							</div>
						@else
							<div class="gallery_box">
								<a href='{{"/evaluations/" . $evaluatedPhoto->id . "/viewEvaluation/" . $user->id }}' class="gallery_photo" title="{{ $evaluatedPhoto->name }}">
									<img src="{{ URL::to("/arquigrafia-images/" . $evaluatedPhoto->id . "_home.jpg") }}" class="gallery_photo" />
								</a>
								<a href='{{"/evaluations/" . $evaluatedPhoto->id . "/viewEvaluation/" . $user->id }}' class="name">
									{{ $evaluatedPhoto->name }}
								</a>
								<br />
							</div>
						@endif
					@endforeach
				@else
					<p>
						@if (Auth::check() && $user->id == Auth::user()->id)
							Você ainda não realizou nenhuma avaliação.
              <a href="{{ URL::to('/') }}">Selecione</a> uma imagem e avalie a arquitetura apresentada nela.
						@else
							Não possui avaliações.
						@endif
					</p>
				@endif
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
