@extends('new_front.app')

@section('content')
  <album-component :album="{{$album}}" :user="{{$user}}" :other_albums="{{$other_albums}}" :other_albums_photos="{{$other_albums_photos}}" :auth="{{Auth::user() ?? 0}}" :photos="{{$photos}}"></album-component>
@endsection
