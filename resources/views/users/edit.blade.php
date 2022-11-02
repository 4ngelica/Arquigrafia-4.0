@extends('new_front.app')

@section('content')
<user-edit-component :user="{{$user}}" :auth="{{Auth::user() ?? 0 }}"></user-edit-component>
@endsection
