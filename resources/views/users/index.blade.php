@extends('layouts.default')

@section('head')

<title>Arquigrafia - Usuários</title>

@stop

@section('content')

  <div class="container">
  
    <h1>Todos Users</h1>
    
    <ul>
    
    @foreach($users as $user)
    	<li><?php echo link_to("/users/".$user->id, $user->name) ?></li>
    @endforeach
    
    </ul>
    
  </div>
  
@stop