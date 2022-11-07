@extends('new_front.app')

@section('content')
  <!-- <login-component ></login-component> -->
  <div class="container-login">
    <h1>Login</h1>
    <h2>Entre com seu login ou e-mail e em seguida digite sua senha:</h2>
    <form method="POST" class="my-3">
      @csrf

      <div class="form-group my-4 d-flex flex-column justify-content-center">
        <div class="col-12  mx-1">
            <label for="login">Login ou E-mail:</label>
            <input type="text" class="form-control" name="login" id="login"  />
        </div>

        <div class="col-12 mx-1">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" name="password" id="password" />
        </div>

      <button type="submit" class="btn btn-primary my-4 mx-1">Login</button>
      <a href="/users/forget">Esqueceu sua senha?</a>
    </div>
  </form>

  <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Login institucional
  </button>

  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <img src="/img/logo.png" class="row" width="200px">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <div class="modal-body">
            <form method="POST" action="{{route('institutional.login')}}" class="my-3 d-flex flex-column justify-content-center">
              @csrf
              <div class="form-group my-4">
                <div class="col-12 mx-1">
                    <label for="institution">Acervo:</label>
                    <select class="form-select" aria-label="Default select example" name="institution" id="institution" >
                      <option value="1">Acervo da Biblioteca da FAUUSP</option>
                      <option value="2">Acervo Quapá</option>
                      <option value="3">Museu Republicano Convenção de Itu</option>
                      <option value="4">Equipe Arquigrafia</option>
                    </select>
                </div>

                <div class="col-12 mx-1">
                    <label for="login">Login ou E-mail:</label>
                    <input type="text" class="form-control" name="login" id="login"  />
                </div>

                <div class="col-12 mx-1">
                    <label for="password">Senha:</label>
                    <input type="password" class="form-control" name="password" id="password" />
                </div>

              <button type="submit" class="btn btn-primary my-4 mx-1">Login</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
