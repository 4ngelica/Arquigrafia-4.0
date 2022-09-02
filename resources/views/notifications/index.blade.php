@extends('layouts.default')

@section('head')

<title>Arquigrafia - Notificações de {{ $user->name }}</title>

@stop

@section('content')
    <div id="content" class="container">
	<?php
        if (Auth::check()) {
            $user = Auth::user();
            $max = count($user->notifications);
	        $unreadNotifications = $user->notifications()->unread()->get();
            $readNotifications = $user->notifications()->read()->get();
	?>
	<h2 class="notifications-title">
        Suas notificações:
        <div id="button-all-container">
            <p id="read-all-sentence">Marcar todas como lidas:</p>
            <div class="read-button" title="Marcar todas como lidas" onclick="readAll();"></div>
        </div>
    </h2>
	@if ($user->notifications->isEmpty())
		<p id="no-notifications">Você não possui notificações.</p>
	@else
	   @include("includes.notes", ['user' => $user, 'max' => $max])
    @endif
 	<?php } ?>
    </div>
@stop
