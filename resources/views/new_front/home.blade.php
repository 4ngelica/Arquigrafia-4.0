@extends('new_front.app')

@section('content')
  <home-component :institution="{{$institution ?? 0}}"></home-component>
@endsection
