@extends('new_front.app')

@section('content')
<evaluation-component :photo="{{$photo}}" :user="{{$user}}" :auth="{{Auth::user() ?? 0 }}"></evaluation-component>
@endsection
