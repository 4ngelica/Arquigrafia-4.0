<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Olá, {{$name}}</h2>

        <div>
             
            Para finalizar o seu cadastro no Arquigrafia, por favor, acesse o link abaixo para confirmar o seu endereço de e-mail.<br/>  
            {{ URL::to('users/verify/' . $verifyCode) }}<br>
            Caso o endereço de e-mail não seja confirmado dentro de 30 dias, a conta será excluída automaticamente.<br>

            Atenciosamente,
            Equipe do Arquigrafia.  

        </div>

    </body>
</html>