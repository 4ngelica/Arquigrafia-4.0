<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h3>Olá, {{ $user->name }}</h3>

		<div>
			<p>Conforme a sua solicitação, segue uma senha temporária para acessar o Arquigrafia.</p>
			<br/>
			<p>Senha: {{$randomPassword}} </p>
			<p>Por favor, clique aqui {{ URL::to('users/login') }} para acessar o sistema com a sua nova senha. </p>
			<p>Recomendamos fortemente que você altere a senha após o login. </p>
			<p>Se você encontrar algum problema para acessar, envie um e-mail para: arquigrafiabr@gmail.com </p>

			<p>
			Atenciosamente,
            Equipe do Arquigrafia.
			</p>

		</div>
	</body>
</html>
