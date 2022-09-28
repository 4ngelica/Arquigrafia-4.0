@extends('new_front.app')

@section('content')
<photo-component :photo="{{$photo}}" :auth="{{Auth::user() ?? 0 }}"></photo-component>
@endsection
