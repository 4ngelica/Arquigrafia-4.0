<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Olá, {{$userName}}!</h2>

    <div>
      Sua sugestão para a imagem {{$imageName}} acaba de ser analisada pelo autor da imagem.<br>
      Por favor, acesse o link abaixo para visualizar a análise.<br>
      {{ URL::to('users/contributions') }}<br><br>

      Atenciosamente,<br><br>
      Equipe do Arquigrafia.
    </div>

  </body>
</html>
