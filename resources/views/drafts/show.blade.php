@extends ('layouts.default')

@section ('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
@stop

@section ('content')
  <div class="container">
    <a href="{{ URL::previous() }}" class="row">Voltar para a página anterior</a>
    <br>
    <h1 class="row">Uploads incompletos (<span class="draft_count">{{ $drafts->getTotal() }}</span>)</h1>
    @include('list')
  </div>
  <div id="mask"></div>
  <div id="draft_window" class="window">
    <div id="registration_delete">
      <p>Tem certeza que deseja excluir estes dados?</p>
      <div id="registration_buttons">
        <a href="#" class="delete_draft_confirm btn">Confirmar</a>
        <a class="btn close" href="#" >Cancelar</a>
      </div>
    </div>
  </div>
  <div class="message_box"></div>
@stop