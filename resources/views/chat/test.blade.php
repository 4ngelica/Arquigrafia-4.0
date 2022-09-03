@extends('layouts.default')

@section('head')

<title>Arquigrafia - Teste </title>
	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/checkbox.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/chat/chat.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/jquery.fancybox.css" />
	<script src="https://js.pusher.com/3.2/pusher.min.js"></script>
	<script type="text/javascript" src="{{ URL::to("/") }}/js/jquery.fancybox.pack.js"></script>
	<script type="text/javascript" src="{{ URL::to("/") }}/js/photo.js"></script>
	<script type="text/javascript" src="{{ URL::to("/") }}/js/chat/chat.js"></script>
	<script type="text/javascript">
		// Defining variables pushed from PHP
		var userID = {{ $user_id }};
		var userName = "{{ $user_name }}";
	</script>

@stop

@section('content')
	<h1>{{ $output }}</h1>
	<p id="message">Heya</p>

	<form action="create" method="GET">
    <p>Criar Chat</p>
		<input type="number" name="participants" value="" placeholder="Participante">
		<input type="text" name="subject" value="" placeholder="Assunto">
		<input type="submit">
	</form>

	<div id="chatbox">
		<ul id="chat-list">

		</ul>
	</div>
	<div id="chat-input-box">
		<input type="number" id="thread-id-input" value="1" placeholder="Chat Id">
		<input type="text" id="message-input" value="" placeholder="Mensagem">
		<button id="send-message">Send</button>
	</div>
@stop
