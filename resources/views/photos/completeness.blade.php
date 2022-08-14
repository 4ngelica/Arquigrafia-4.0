@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
  <script src="{{ URL::to('/js/dist/photosCompleteness.bundle.js') }}"></script>
@stop

@section('content')
  <div class="content">
    <div id="photos-completeness-content">
      <!-- HERE, VUE.JS WILL RENDER CONTENT - CONTRIBUTIONS -->
    </div>
  </div>
@stop

