@extends('new_front.app')

@section('content')
  <suggestions-component :auth="{{Auth::user() ?? 0 }}" :suggestions="{{$suggestions}}"></suggestions-component>
@endsection
