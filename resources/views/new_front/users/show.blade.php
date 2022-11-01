@extends('new_front.app')

@section('content')
<profile-component :user="{{$user}}" :auth="{{Auth::user() ?? 0 }}"></profile-component>
@endsection
