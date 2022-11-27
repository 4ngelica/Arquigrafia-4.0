@extends('new_front.app')

@section('content')
<evaluate-component :photo="{{$photo}}" :user="{{$user}}" :auth="{{Auth::user() ?? 0 }}" :tags="{{$tags}}"></evaluate-component>
@endsection
