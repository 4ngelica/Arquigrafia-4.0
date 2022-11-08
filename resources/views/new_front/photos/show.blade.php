@extends('new_front.app')

@section('content')
<photo-component :photo="{{$photo}}" :user="{{$user}}" :comments="{{$comments}}":auth="{{Auth::user() ?? 0 }}" :tags="{{$tags}}"></photo-component>
@endsection
