@extends('/layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
  <script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>
  <script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>
  <link rel="stylesheet" type="text/css" media="screen"
    href="{{ URL::to("/") }}/css/checkbox.css" />
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
      @if (Auth::check() && Session::get('institutionId') == $institution->id)
        <!-- <a href= '{{"/institutions/" . $institution->id . "/edit" }}' title="Editar perfil" >-->
        @if ($institution->photo != "")
          <div class="div_avatar_size_inst" >
            <img class ="class_img_avatar"  class="avatar" src="{{ asset($institution->photo) }}"
              class="user_photo_thumbnail"/>
          </div>
        @else
          <img class="avatar" src="{{ asset("img/avatar-institution.png") }}" width="68" height="68"
            class="user_photo_thumbnail"/>
        @endif
        <!--   </a>-->
      @else
        @if($institution->photo != "")
          <div class="div_avatar_size_inst" >
            <img class ="class_img_avatar" class="avatar" src="{{ asset($institution->photo) }}"
              class="user_photo_thumbnail"/>
          </div>
        @else
          <img class="avatar" src="{{ asset("img/avatar-institution.png") }}" width="68" height="68"
            class="user_photo_thumbnail"/>
        @endif
      @endif
      <div class="info">
        <h1>{{ $institution->name}}</h1>
        @if ( !empty($institution->city) && Session::has('institutionId'))
          <p >{{ ucfirst($institution->city) }}</p>
        @endif

        @if (Auth::check() && !is_null($follow))
          @if (!empty($follow) && $follow == true )
            <a href="{{ URL::to("/friends/followInstitution/" . $institution->id) }}"
              id="single_view_contact_add">Seguir</a><br />
          @else
            <div id="unfollow-button">
              <a href="{{ URL::to("/friends/unfollowInstitution/" . $institution->id) }}">
                <p class="label success new-label"><span>Seguindo</span></p>
              </a>
            </div>
          @endif
        @endif
        @if ($responsible == true)
          <a href="{{ URL::to("/institutions/" . $institution->id . "/edit") }}"
            id="single_view_contact_add" title="Edite o seu perfil">Editar perfil</a><br />
        @endif
      </div>
      <div class="countside">Últimas imagens compartilhadas({{ count($photos) }})</div>

	  <div class="count"><br><a href="{{ URL::to('/institutions/' . $institution->id . '/allphotos/') }}"
              id="single_view_contact_add">Ver todas></a><br /></div>

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
            Não há imagens.
          </p>
        </div>
      </div>
    @endif
  </div>
  <br>
  <br>
  @if (Auth::check() && $institution->id == Session::get('institutionId'))
    <div class="container row">
      <div class="twelve columns">
        <hgroup class="profile_block_title">
          <h3><i class="upload"></i>Uploads incompletos</h3>
          @if ($drafts->count())
            <div class="two columns">
              <a href="{{ URL::to('/drafts') }}">Visualizar todos</a>
            </div>
          @endif
        </hgroup>
        <div class="profile_box">
          @include('list')
        </div>
      </div>
    </div>
  @endif
  <!-- USUÁRIO -->
  <div class="container row">
    <div class="six columns">
      <hgroup class="profile_block_title">
        <h3><i class="profile"></i>Informações</h3>
      </hgroup>
      <ul>
        @if ( !empty($institution->name) )
          <li><strong>Nome: </strong>{{ $institution->name}}</li>
        @endif
        @if ( !empty($institution->email) )
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
          <li>
            <strong>Site pessoal: </strong>
            <a href="{{ $institution->site }}" target="_blank">{{ $institution->site }}</a>
          </li>
        @endif
        @if ( !empty($institution->address) )
          <li>
            <strong>Endereço: </strong>
            {{ $institution->address }}
          </li>
        @endif
        @if ( !empty($institution->phone) )
          <li>
            <strong>Telefone: </strong>
            {{ $institution->phone }}
          </li>
        @endif
      </ul>
    </div>
    <div class="six columns">
      <hgroup class="profile_block_title">
        <h3><i class="follow"></i>
          Seguidores ({{$institution->followersInstitutions->count()}})
        </h3>
        <!--<a href="#" id="small" class="profile_block_link">Ver todos</a>-->
      </hgroup>
      <!--   BOX - AMIGOS   -->
      <div class="profile_box">
        @foreach($institution->followersInstitutions as $follower)
        <div class="gallery_box_inst">
          <a href= "{{ URL::to('/users/'. $follower->id) }}"
            class="gallery_box_inst" title="{{$follower->name}}" >
            @if ($follower->photo != "")
              <img width="40" height="40" class="avatar"
              src="{{ asset($follower->photo) }}" class="user_photo_thumbnail"
              class="gallery_box_inst" />
              </a>
            @else
              <img width="40" height="40" class="avatar"
              src="{{ asset("img/avatar-60.png") }}" class="user_photo_thumbnail"
              class="gallery_box_inst"/>
              </a>
            @endif
            <a href="{{ URL::to("/users/". $follower->id) }}" class="name_text_follow">
               {{ Str::limit(ucfirst($follower->name), 5) }}
              </a>
        </div>
        @endforeach
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
        <?php $albums = $institution->albums; ?>
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
            @if (Auth::check() && Session::get('institutionId') == $institution->id)
              Você ainda não tem nenhum álbum. Crie um <a href="{{ URL::to('/albums/create') }}">aqui</a>
            @else
              Não possui álbuns.
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
  <div id="draft_window" class="window">
    <div id="registration_delete">
      <p>Tem certeza que deseja excluir estes dados?</p>
      <div id="registration_buttons">
        <a href="#" class="delete_draft_confirm btn">Confirmar</a>
        <a class="btn close" href="#" >Cancelar</a>
      </div>
    </div>
  </div>
  <div class="message_box"></div>
@stop
