@extends('new_front.app')

@section('content')
<photo-component :photo="{{$photo}}" :user="{{$user}}" :auth="{{Auth::user() ?? 0 }}" :tags="{{$tags}}" :likes="{{$likes}}" :auth_like="{{$authLike ?? 0}}" :lat_lng="{{$latLng}}" :license="{{$license}}" :suggestion_fields="{{$suggestionFields}}" :institution="{{$institution}}"></photo-component>
@endsection
