@extends('new_front.app')

@section('content')
<profile-component :user="{{$user}}" :auth="{{Auth::user() ?? 0 }}" :photos="{{$photos}}" :albums="{{$albums}}" :evaluations="{{$evaluations}}" :following_number="{{$followingNumber}}" :followers_number="{{$followersNumber}}" :is_following="{{$isFollowing}}"></profile-component>
@endsection
