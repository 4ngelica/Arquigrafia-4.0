<nav class="navbar navbar-expand-lg py-5">
  <div class="container">
    @guest
    <div class="navbar-logo col-md-4 d-none d-md-block">
      <a class="navbar-brand" href="/home">
        <img src="{{asset('/img_scenario4/logo.chou.arquigrafia.webp')}}" alt="" width="223" height="33">
      </a>
    </div>
    <div class="navbar-logo col-md-4 d-md-none">
      <a class="navbar-brand" href="/home">
        <img src="{{asset('/img_scenario4/logo.chou.a2.webp')}}" alt="" height="33">
      </a>
    </div>
    @endguest
    @auth
    <div class="navbar-logo col-md-4 px-2">
      <a class="navbar-brand" href="/home">
        <img src="{{asset('/img_scenario4/logo.chou.a2.webp')}}" alt="" height="33">
      </a>
    </div>
    @endauth
    <div class="col-md-4 col-8 d-flex justify-content-center">
      <form class="d-flex search-form" method="GET" action="{{route('search')}}">
        <input class="form-control me-2 search-bar" name="search" type="search" placeholder="" aria-label="Buscar" value="{{old('search')}}">
        <button class="search-button" type="submit">
        </button>
      </form>
    </div>
    <button class="navbar-toggler col-md-3 text-right px-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end col-md-4" id="navbarSupportedContent">
      @guest
      <div class="nav-menu col-12 d-flex justify-content-end flex-lg-row flex-column">
        <a href="/users/account" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2">Criar uma conta</a>
        <a href="/users/login" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2">Entrar</a>
      </div>
      @endguest
      @auth
      <div class="nav-menu col-12 d-flex justify-content-end flex-lg-row flex-column">
        <a href="{{'/users/' . Auth::user()->_id}}" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Perfil</a>
        <a href="#" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Notificações</a>
        <a href="/photos/upload" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Enviar Imagem</a>
        <a href="/albums" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Meus Álbuns</a>
        <a href="#" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Mensagens</a>
        <a href="/contributions" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Minhas Constribuições</a>
        <a href="/suggestions" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Sugestões</a>
        <a href="{{'/users/' . Auth::user()->_id . '/edit'}}" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Configurações</a>
        <a href="/users/logout" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2 d-lg-none">Sair</a>
        <a href="/photos/upload" class="nav-item d-lg-flex d-none mx-1 px-3 py-2 align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"></path>
            <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"></path>
          </svg>
        </a>
        <li class="nav-item dropdown d-lg-flex d-none mx-1 px-3 py-2 align-items-center">
          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notificações">
            <i>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"></path>
              </svg>
            </i>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          </ul>
        </li>
        <li class="nav-item dropdown d-lg-flex d-none mx-1 px-3 py-2 align-items-center">
          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Mensagens">
            <i class="bi bi-messenger">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-messenger" viewBox="0 0 16 16">
                <path d="M0 7.76C0 3.301 3.493 0 8 0s8 3.301 8 7.76-3.493 7.76-8 7.76c-.81 0-1.586-.107-2.316-.307a.639.639 0 0 0-.427.03l-1.588.702a.64.64 0 0 1-.898-.566l-.044-1.423a.639.639 0 0 0-.215-.456C.956 12.108 0 10.092 0 7.76zm5.546-1.459-2.35 3.728c-.225.358.214.761.551.506l2.525-1.916a.48.48 0 0 1 .578-.002l1.869 1.402a1.2 1.2 0 0 0 1.735-.32l2.35-3.728c.226-.358-.214-.761-.551-.506L9.728 7.381a.48.48 0 0 1-.578.002L7.281 5.98a1.2 1.2 0 0 0-1.735.32z"></path>
              </svg>
            </i>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          </ul>
        </li>
        <li class="nav-item dropdown d-lg-flex d-none mx-1 px-2 py-2 align-items-center">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if(Auth::user()->photo)
              <img class="nav-item dropdown rounded-circle" src="{{asset(Auth::user()->photo)}}" alt="" width="40" height="40">
            @else
              <img class="nav-item dropdown rounded-circle" src="{{asset('/img_scenario4/avatar-48.webp')}}" alt="" width="40" height="40">
            @endif
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li class="d-flex align-items-center bg-light p-1">
              @if(Auth::user()->photo)
              <img class="nav-item dropdown rounded-circle" src="{{asset(Auth::user()->photo)}}" alt="" width="60" height="60">
              @else
              <img class="nav-item dropdown rounded-circle" src="{{asset('/img_scenario4/avatar-48.webp')}}" alt="" width="60" height="60">
              @endif
              <div class="px-2">
                <p class="m-0 font-weight-bold" >{{Auth::user()->name}}</p>
                <p class="m-0" >{{Auth::user()->email}}</p>
              </div>
            </li>
            <li><a class="dropdown-item" href="{{'/users/' . Auth::user()->_id}}">Perfil</a></li>
            <li><a class="dropdown-item" href="/contributions">Minhas Contribuições</a></li>
            <li><a class="dropdown-item" href="/suggestions">Sugestões</a></li>
            <li><a class="dropdown-item" href="/albums">Meus Álbuns</a></li>
            <li><a class="dropdown-item" href="{{'/users/' . Auth::user()->_id . '/edit'}}">Configurações</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a href="/users/logout" class="nav-btn mx-lg-1 px-1 px-lg-3 py-2">Sair</a></li>
          </ul>
        </li>
      </div>
      @endauth
    </div>
  </div>
</nav>
