@extends('new_front.app')

@section('content')
  <contributions-component :auth="{{Auth::user() ?? 0 }}" :reviews="{{$reviews}}" :editions="{{$editions}}"  :accepted_editions="{{$accepted_editions}}" :refused_editions="{{$refused_editions}}" :waiting_editions="{{$waiting_editions}}" :accepted_reviews="{{$accepted_reviews}}" :refused_reviews="{{$refused_reviews}}" :waiting_reviews="{{$waiting_reviews}}"></contributions-component>
@endsection
