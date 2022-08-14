@extends('layouts.default')

@section('head')

<title>Arquigrafia - Fotos</title>

@stop

@section('content')

  <div class="container">

    <h1>Todas as fotos</h1>
    
    <ul>
    
    @foreach($photos as $photo)
    	<li>{{ link_to ("photos/{$photo->id}", $photo->name) }}</li>
    @endforeach
    
    </ul>
    
  </div>
    
@stop