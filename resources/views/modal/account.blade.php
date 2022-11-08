@extends('new_front.app')

@section('content')
<div class="container">

  <div id="registration">

    <div class="five columns offset-by-three">
      <h1>Cadastro</h1>
      <p>Faça seu cadastro para poder compartilhar imagens no Arquigrafia.
      </p>
    </div>

      <form method="POST" class="my-3" action="{{route('register')}}">
        @csrf

        <div class="form-group my-4 d-flex flex-column justify-content-center">
        <div class="col-12  mx-1">
            <label for="name">Nome:</label>
            <input type="text" class="form-control" name="name" id="name"  />
        </div>

          <div class="col-12  mx-1">
              <label for="login">Login:</label>
              <input type="text" class="form-control" name="login" id="login"  />
          </div>

          <div class="col-12  mx-1">
              <label for="email">Email:</label>
              <input type="text" class="form-control" name="email" id="email"  />
          </div>

          <div class="col-12 mx-1">
              <label for="password">Senha:</label>
              <input type="password" class="form-control" name="password" id="password" />
          </div>

          <div class="col-12 mx-1">
              <label for="password_confirmation">Repita a senha:</label>
              <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" />
          </div>

          <div class="col-12 mx-1">
              <label for="terms">Li e aceito os <a href="{{ URL::to('/termos') }}" target="_blank" style="text-decoration: underline;">termos de compromisso</a> </label>
              <input class="form-check-input" type="checkbox" class="form-control" name="terms" id="terms" />
          </div>

        <button type="submit" class="btn btn-primary my-4 mx-1">Registrar</button>
      </div>
    </form>
@endsection
