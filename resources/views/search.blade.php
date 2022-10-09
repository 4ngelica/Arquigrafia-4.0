@extends('layouts.default')

@section('head')

<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

<!-- ISOTOPE -->
<script src="{{ URL::to("/") }}/js/jquery.isotope.min.js"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/panel.js"></script>
<script src="{{ URL::to('/js/searchPagination.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ URL::to('/css/tabs.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/album.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/checkbox-edition.css" />
<script>

    var paginators = {
      add: {
        currentPage: {{ $page }},
        maxPage: {{ $maxPage }},
        url: '{{ $url }}',
        loadedPages: [1],
        selectedItems: 0,
        searchQuery: '',
        selected_photos: 0,
      },
    };
    console.log({{$page}});
    var coverPage = 1;
    var covers_counter = 0;
    var update = null;
  </script>
@stop

@section('content')
  <!--   MEIO DO SITE - ÁREA DE NAVEGAÇÃO   -->
  <div id="content">

    <div class="container">
      <div id="search_result" class="twelve columns row">
        <h1>
          @if ($city != "")
              Resultados encontrados para: "{{ ucwords($query) }}" da cidade de "{{ucwords($city)}}"
          @elseif ( isset($binomial_option) )
            Resultados encontrados para arquiteturas com característica:
            @if ( isset($value) )
              {{ $value }}%
            @endif
            {{ $binomial_option }}
          @else
            Resultados encontrados para: {{ $query }}
          @endif
        </h1>
       <!-- To data search  -->

       <!-- -->
        @if( count($tags) != 0 )
          <p style="display: inline">
            Tags contendo o termo:
            @foreach($tags as $k => $tag)
              @if ($k != count($tags)-1 )
                <form id="{{$k}}" action="{{ URL::to("/") }}/search" method="post" accept-charset="UTF-8" style="display: inline">
                  @csrf
                  <input type="hidden" name="q" value="{{$tag->name}}"/>
                  <a href="javascript: submitform({{$k}});">{{ $tag->name }}</a>,
                </form>
              @else
                <form id="{{$k}}" action="{{ URL::to("/") }}/search" method="post" accept-charset="UTF-8" style="display: inline">
                @csrf
                <input type="hidden" name="q" value="{{$tag->name}}"/>
                  <a href="javascript: submitform({{$k}});">{{ $tag->name }}</a>
                </form>
              @endif
            @endforeach
          </p>
          <script type="text/javascript">
            function submitform(object)
            {
              document.getElementById(object).submit();
            }
          </script>
        @endif


        @if( count($authors) != 0 )
          <p style="display: inline">
            Autores contendo o termo:
            @foreach($authors as $v => $author)

              @if ($v != count($authors)-1 )
                <form id="a{{$v}}" action="{{ URL::to("/") }}/search" method="post" accept-charset="UTF-8" style="display: inline">
                  @csrf
                  <input type="hidden" name="q" value="{{$author['name']}}"/>
                  <input type="hidden" name="type" value="a"/>
                  <a href="javascript: submitformS('a{{$v}}');">{{ $author['name'] }}</a>;
                </form>
              @else
                <form id="a{{$v}}" action="{{ URL::to("/") }}/search" method="post" accept-charset="UTF-8" style="display: inline">
                @csrf
                <input type="hidden" name="q" value="{{$author['name']}}"/>
                <input type="hidden" name="type" value="a"/>
                  <a href="javascript: submitformS('a{{$v}}');">{{ $author['name'] }}</a>
                </form>
              @endif
            @endforeach
          </p>
          <script type="text/javascript">
            function submitformS(objectAuthor)
            {
              document.getElementById(objectAuthor).submit();
            }
          </script>
        @endif

        @if($photos)
          @if ( count($photos) < 1 && !isset($binomial_option) )
            <p>Não encontramos nenhuma imagem com o termo {{ $query }}.</p>
          @elseif (count($photos) < 1)
            <p>Não foi encontrada nenhuma imagem com arquitetura classificada como
            {{ lcfirst($binomial_option) }} </p>
          @else
            <p>Foram encontradas {{ $photosAll }} imagens.</p>
          @endif
        @endif
        <p>Faça uma <a href="{{ URL::to('/search/more') }}">busca avançada aqui</a>.</p>
        <p><a href="{{ URL::previous() }}">Voltar para página anterior</a></p>
      </div>
    </div>
    <input type="hidden" id="pgVisited" value="{{$pageVisited}}">
    <input type="hidden" id="pageCurrent1" value="{{$page}}">
    <input type="hidden" id="urlType" value="simple">

    <div class="container">
      <div id="add1" class="twelveMid columns add" >
        @if ( isset($users) && $users != null )
          @if($users->count() > 0 )
            @include('users.includes.searchResult_include')
          @endif
        @else
          <div class="wrap">
          </div>
        @endif
      </div>
    </div>

    <!-- FOTOS PAGINADAS -->
    @include('includes.result-search')
    <!-- FIM FOTOS PAGINADAS -->
  </div>
  <!--   FIM - MEIO DO SITE   -->
@stop
