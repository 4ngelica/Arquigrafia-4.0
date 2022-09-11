@extends('new_front.app')

@section('content')
@if($search)
  <search-component :users="{{ $users }}" :photos="{{ $photos }}" :authors="{{ $authors }}" :search=" {{ $search }} " :photoscount=" {{ $photosCount }} " :userscount=" {{ $usersCount }}" :authorscount=" {{ $authorsCount }}"></search-component>
@else
  <div class="container">
    <h1>Resultados encontrados para: </h1>
    <h2>Não encontramos nenhum resultado com o termo .</h2>
    <a href="#">Faça uma busca avançada aqui.</a>
    <a href="#">Voltar para página anterior.</a>
  </div>
@endif
@endsection
