<nav class="navbar navbar-expand-lg py-5">
  <div class="container">
    @guest
    <div class="navbar-logo col-lg-4">
      <a class="navbar-brand" href="/home">
        <img src="{{asset('/img/logo.chou.arquigrafia.png')}}" alt="" width="223" height="33">
      </a>
    </div>
    @endguest
    @auth
    <div class="navbar-logo col-lg-4">
      <a class="navbar-brand" href="/home">
        <img src="{{asset('/img/logo.chou.a2.png')}}" alt="" height="33">
      </a>
    </div>
    @endauth
    <div class="col-lg-4 col-8 d-flex justify-content-center">
      <form class="d-flex search-form" method="POST" action="{{route('search')}}">
        @csrf
        <input class="form-control me-2 search-bar" name="search" type="search" placeholder="" aria-label="Buscar" value="{{old('search')}}">
        <button class="search-button" type="submit">
        </button>
      </form>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end col-md-4" id="navbarSupportedContent">
      <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul> -->
      <!-- <div class="col-md-6 d-flex justify-content-center">
        <form class="d-flex search-form">
          <input class="form-control me-2 search-bar" type="search" placeholder="" aria-label="Buscar">
          <button class="search-button" type="submit">
          </button>
        </form>
      </div> -->

      @guest
      <div class="nav-menu col-12 d-flex justify-content-end flex-lg-row flex-column">
        <a href="/users/account" class="nav-btn">Criar uma conta</a>
        <a href="/users/login" class="nav-btn">Entrar</a>
        <!-- <button type="button" name="button">Criar conta</button> -->
        <!-- <button type="button" name="button">Login</button> -->
      </div>
      @endguest
      @auth
      <div class="nav-menu col-12 d-flex justify-content-end flex-lg-row flex-column">
        <a href="#" class="nav-btn">User</a>
        <a href="/users/logout" class="nav-btn">Sair</a>
        <!-- <button type="button" name="button">User</button> -->
        <!-- <button type="button" name="button">Logout</button> -->
      </div>
      @endauth
    </div>
  </div>
</nav>
