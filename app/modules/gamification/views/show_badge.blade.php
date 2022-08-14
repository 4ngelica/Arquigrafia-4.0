@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
@stop

@section('content')

  <div class="container">
    <div id="badge_section" class="twelve columns">
      <h1>{{ $badge->name }}</h1>
      <div class="two columns">
        <img id="badge_img" src="{{ asset('/img/badges/' . $badge->image ) }}" height="128" width="128">
      </div>
      <div id="badge_description_container" class="eight columns">
        <p>{{ ucfirst($badge->description) }}.</p>
      </div>
    </div>
  </div>
  <style type="text/css">
    #badge_description_container {
      position: absolute;
      top: 50%;
      left: 160px;
    }
    #badge_description_container p {
      font-size: 16px;
      color: #333;  
    }
  </style>
@stop