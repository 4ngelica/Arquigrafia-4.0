<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Olá, {{$name}}</h2>

        <div>
             
            Bem-vindo ao Arquigrafia<br/>
            Utilize o seu nome de usuário ({{$login}}) e senha cadastrados pelo aplicativo<br />  
            para acessar também o site do Arquigrafia: {{ URL::to('/') }}<br>

            Atenciosamente,
            Equipe do Arquigrafia.  

        </div>

    </body>
</html>