@extends('new_front.app')

@section('content')
<profile-component :user="{{$user}}"></profile-component>
@endsection
