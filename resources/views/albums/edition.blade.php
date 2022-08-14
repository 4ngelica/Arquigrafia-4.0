@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Edição de {{ $album->title }}</title>
  <link rel="stylesheet" type="text/css" href="{{ URL::to('/css/tabs.css') }}">
  <script src="{{ URL::to('/js/albums-covers.js') }}"></script>
  <script src="{{ URL::to('/js/album.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/custom-tooltip.css" />
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/checkbox-edition.css" />
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/album.css" />
  <script src="{{ URL::to('/js/jquery.tooltipster.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/tooltipster.css" />
  <script>
    var paginators = {
      add: {
        currentPage: 1,
        maxPage: {{ $maxPage }},
        url: '{{ $url }}',
        loadedPages: [1],
        selectedItems: 0,
        searchQuery: '',
        selected_photos: 0,
      },
      rm: {
        currentPage: 1,
        maxPage: {{ $rmMaxPage }},
        url: '{{ $rmUrl }}',
        loadedPages: [1],
        selectedItems: 0,
        searchQuery: '',
        selected_photos: 0,
      }
    };
    var searchQuery = '';
    var coverPage = 1;
    var maxCoverPage = {{ ceil($album->photos->count() / 48) }};
    var album = {{ $album->id }};
    var covers_counter = 0;
    var which_photos = 'user';
    var update = null;
  </script>
@stop

@section('content')
  <div class="container">
    <div class="twelve columns">
      <h1 id="album_title">Edição de {{ $album->title }}</h1>
      <p>* Os campos a seguir são obrigatórios.</p>
      </p>      
    </div>
    <div class="twelve columns">
      <div class="tabs">
        <ul class="tab-links">
          <li class="active"><a href="#album_info">Informações do álbum</a></li>
          <li><a href="#album_images">Imagens do álbum</a></li>
          <li><a href="#add_images">Inserção de imagens</a></li>
        </ul>
        <div class="tab-content">
          <div id="album_info" class="tab active">
            {{ Form::open(array('url' => '/albums/' . $album->id . '/update/info', 'method' => 'post')) }}
              <div class="eleven columns">
                <div class="five columns">
                  <div class="four columns center">
                    <p><label for="cover_img">Capa do álbum</label></p>
                    <div class="img_container"> 
                      @if( isset($album->cover_id) )
                        <img id="cover-img" src="{{ URL::to('/arquigrafia-images/' . $album->cover_id . '_view.jpg') }}">
                      @else
                        <img id="cover-img" class="hidden" 
                          src="{{ URL::to('/arquigrafia-images/' . $album->cover_id . '_view.jpg') }}">
                        <div class="no_cover">
                          <p> Álbum sem capa </p>
                        </div>
                      @endif
                      <?php $photos = $album_photos; ?>
                      @if ($photos->count() > 0)
                        <span class="visible"><a class="cover_btn" href="#">Alterar capa</a></span>
                      @else
                        <span><a class="cover_btn" href="#">Alterar capa</a></span>
                      @endif
                    </div>
                    @if ($photos->count() > 0)
                      <a class="cover_btn" href="#">Alterar capa</a>
                    @else
                      <a class="cover_btn hidden" href="#">Alterar capa</a>
                    @endif
                    {{ Form::hidden('cover', $album->cover_id, ['id' => 'cover']) }}
                  </div>
                </div>
                <div id="info" class="five columns">
                  <div class="four columns"><p>{{ Form::label('title', 'Título*') }}</p></div>
                  <div class="four columns">
                    <p>{{ Form::text('title', $album->title) }} <br>
                      <div class="error"></div>
                    </p>
                  </div>
                  <div class="four columns"><p>{{ Form::label('description', 'Descrição') }}</p></div>
                  <div class="four columns">
                    <p>{{ Form::textarea('description', $album->description) }}</p>
                  </div>
                  <div class="four columns">
                    <p>{{ Form::submit('ATUALIZAR', array('class' => 'btn')) }}</p>
                  </div>
                </div>
              </div>
            {{ Form::close() }}
          </div>
          <div id="album_images" class="tab rm">
            <div class="eleven columns select_options rm">
              {{ Form::open(array('url' => '', 'method' => '',
                'class' => 'eleven columns alpha omega album_form')) }}
                <div class="seven columns alpha omega">
                  <div class="four columns alpha omega block">
                    <input class="select_all" type="checkbox">
                    <label for="">Selecionar imagens desta página</label>
                    <p class="filter">Todas as imagens ({{ $album->photos->count() }})</p>
                  </div>
                  <div class="three columns alpha omega block">
                    <p class="selectedItems"></p>
                  </div>
                </div>
                <div class="four columns alpha omega block">
                    <input type="text" class="search_bar" placeholder="Imagens do seu álbum"
                      title="Pesquise as imagens do seu álbum pelo nome">
                    <input type="button" class="search_bar_button cursor" value="FILTRAR">
                </div>
              {{ Form::close() }}
            </div>
            <div id="rm" class="eleven columns rm">
              <img class="loader" src="{{ URL::to('/img/ajax-loader.gif') }}" />
              <?php 
                $photos = $album_photos;
                $type = 'rm';
              ?>
              @if ($photos->count() > 0)
                @include('albums.includes.album-photos-edit')
              @else
                <p>Seu álbum está vazio.</p>
              @endif
            </div>
            <div class="eleven columns block rm">
              <div class="eight columns alpha buttons">
                <input type="button" class="btn less less-than" value="&lt;&lt;">
                <input type="button" class="btn less-than" value="&lt;">
                <p>1 / {{ $rmMaxPage }}</p>
                <input type="button" class="btn greater-than" value="&gt;">
                <input type="button" class="btn greater greater-than" value="&gt;&gt;">
              </div>
              <div class="three columns omega">
                <input type="button" id="rm_photos_btn" class="btn right" value="REMOVER IMAGENS MARCADAS">
              </div>
            </div>
          </div>
          <div id="add_images" class="tab add">
            <div class="eleven columns select_options add">
              {{ Form::open(array('url' => '', 'method' => '',
                'class' => 'eleven columns alpha omega album_form')) }}
                <div class="seven columns alpha omega">
                  <div class="four columns alpha omega block">
                    <input class="select_all" type="checkbox">
                    <label for="">Selecionar imagens desta página</label>
                    <p class="filter">Todas as imagens ({{ $other_photos_count }})</p>
                  </div>
                  <div class="three columns alpha omega block">
                    <p class="selectedItems"></p>
                  </div>
                </div>
                <div class="four columns alpha omega block">
                  <div class="four columns alpha omega"> 
                   <input type="text" class="search_bar"
                      title="Pesquise imagens fora do seu álbum pelo nome">
                    <input type="button" class="add search_bar_button cursor" value="FILTRAR">
                  </div>
                  <div class="four columns alpha omega block">
                    <input type="radio" name="which_photos" value="user" checked/><label for="">Suas imagens</label>
                    <input type="radio" name="which_photos" value="all" /><label for="">Imagens do Arquigrafia</label>
                  </div>
                </div>
              {{ Form::close() }}
            </div>
            <div id="add" class="eleven columns add">
              <img class="loader" src="{{ URL::to('/img/ajax-loader.gif') }}" />
              <?php 
                $photos = $other_photos;
                $type = 'add';
              ?>
              @if ($photos->count() > 0)
                @include('albums.includes.album-photos-edit')
              @else
                <p>Não foi encontrada nenhuma imagem sua para ser adicionada.</p>
              @endif
            </div>
            <div class="eleven columns block add">
              <div class="eight columns alpha buttons">
                <input type="button" class="btn less less-than" value="&lt;&lt;">
                <input type="button" class="btn less-than" value="&lt;">
                <p>1 / {{ $maxPage }}</p>
                <input type="button" class="btn greater-than" value="&gt;">
                <input type="button" class="btn greater greater-than" value="&gt;&gt;">
              </div>
              <div class="three columns omega">
                <input type="button" id="add_photos_btn" class="btn right" value="INSERIR IMAGENS MARCADAS">
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="mask"></div>
  <div id="form_window" class="form window">
    <a class="close" href="#" title="FECHAR">Fechar</a>
    <div id="covers_registration"></div>
  </div>
  <div class="message_box"></div>
@stop