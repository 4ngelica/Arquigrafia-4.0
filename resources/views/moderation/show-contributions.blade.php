@extends('new_front.app')

@section('content')
<div class="container">

  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Revisões</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Edições</button>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
      Suas Revisões
      Total: 1

      Aguardando: 1

      Aceitas: 0

      Recusadas: 0

      Filtros:

      Todas
      Aceitas
      Recusadas
      Aguardando
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      Suas Edições
      Total: 1

      Aguardando: 1

      Aceitas: 0

      Recusadas: 0

      Filtros:

      Todas
      Aceitas
      Recusadas
      Aguardando
    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
  </div>
</div>

@endsection
