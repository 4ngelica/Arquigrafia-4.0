@if( $user->equal(Auth::user()) && $gamified )
<div id="my_points" class="container row">
  <div class="twelve columns">
    <hgroup class="profile_block_title">
      <h3><i class="points"></i>
        Minha Pontuação
      </h3>
    </hgroup>
    <div class="profile_box">
      @if ( !$userPoints && !$userWaitingPoints && !sizeof($refusedSuggestions))
        <p>Você ainda não possui nenhum ponto.</p>
      @else
        <p><strong>Pontuação atual:</strong> {{ $userPoints }}</p>
        <p><strong>Pontuação a ser aprovada:</strong>  {{ $userWaitingPoints }}</p>
        <a class="num-suggestions-profile" href="{{ URL::to('/users/contributions') }}">
          <p><strong>Número de sugestões aceitas:</strong>  {{ sizeof($acceptedSuggestions) }}
        </a>
        <a class="num-suggestions-profile" href="{{ URL::to('/users/contributions') }}">
          <p><strong>Número de sugestões aguardando aprovação:</strong> {{ sizeof($waitingSuggestions) }}
        </a>
        <a class="num-suggestions-profile" href="{{ URL::to('/users/contributions') }}">
          <p><strong>Número de sugestões recusadas:</strong> {{ sizeof($refusedSuggestions) }}
        </a>
      @endif
    </div>
  </div>
</div>
@endif
