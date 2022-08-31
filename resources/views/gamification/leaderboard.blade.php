@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>
@stop

@section('content')
  @if (Session::get('message'))
    <div class="container">
      <div class="twelve columns">
        <div class="message">{!! Session::get('message') !!}</div>
      </div>
    </div>
  @endif
  <div class="container">
    <div class="twelve columns">
      <div id="leaderboard" class="ten columns offset-by-one">
        <a href="{{ URL::previous() }}">Voltar para a página anterior</a>
        <br>
        <br>
        <h1>Quadro dos Maiores Colaboradores</h1>
        <div class="paginator">
          <div class="three columns alpha omega">
            <p>
              @if ($users->getCurrentPage() <= 1)
                <a id="less" href="#" class="disabled less-than" onclick="return false;"> &lt; </a>
              @else
                <a id="less"
                  href="{{ URL::to('/leaderboard?type=' . $score_type . '&page=' . ($users->getCurrentPage() - 1)) }}"
                  class="less-than"> &lt; </a>
              @endif
              &nbsp;
              {{ Form::text('page', $users->getCurrentPage(),
                array('style' => 'width: 30px;', 'class' => 'page_number')) }}
              / {{ $users->getLastPage() }}
              &nbsp;
              @if ($users->getCurrentPage() >= $users->getLastPage())
                <a id="greater" href="#" class="disabled greater-than" onclick="return false;"> &gt; </a>
              @else
                <a id="greater"
                  href="{{ URL::to('/leaderboard?type=' . $score_type . '&page=' . ($users->getCurrentPage() + 1)) }}"
                  class="greater-than"> &gt; </a>
              @endif
            </p>
          </div>
          @if( Auth::check() )
            <div class="three columns">
              <p>
                <a id="find_me"
                href="{{ URL::to('/leaderboard?type=' . $score_type . '&page=' . $user_page) }}">
                Encontrar minha localização
                </a>
              </p>
            </div>
          @else
            <div class="three columns">&nbsp;</div>
          @endif
          <div class="four columns omega">
            <p class="right">
              Ordenar por número de {{ Form::select('score_type',
                [
                  'uploads' => 'uploads',
                  'evals' => 'avaliações'
                ], $score_type, array('class' => 'score_type')) }}
            </p>
          </div>
        </div>
        <table class="form-table row" width="100%" cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <th class="edge">Posição</th>
              <th colspan="2" class="middle">Colaborador</th>
              <th class='score_type_header edge'>
                @if ($score_type == 'uploads')
                  Uploads
                @else
                  Avaliações
                @endif
              </th>
            </tr>
          </thead>
          <tbody>
            @include('leaderboard_users')
          </tbody>
        </table>
        <div class="two columns alpha">
          @if ($users->getCurrentPage() <= 1)
            <a id="less" href="#" class="disabled less-than" onclick="return false;"> &lt; </a>
          @else
            <a id="less"
              href="{{ URL::to('/leaderboard?type=' . $score_type . '&page=' . ($users->getCurrentPage() - 1)) }}"
              class="less-than"> &lt; </a>
          @endif
          &nbsp;
          {{ Form::text('page', $users->getCurrentPage(),
            array('style' => 'width: 30px;', 'class' => 'page_number')) }}
          / {{ $users->getLastPage() }}
          &nbsp;
          @if ($users->getCurrentPage() >= $users->getLastPage())
            <a id="greater" href="#" class="disabled greater-than" onclick="return false;"> &gt; </a>
          @else
            <a id="greater"
              href="{{ URL::to('/leaderboard?type=' . $score_type . '&page=' . ($users->getCurrentPage() + 1)) }}"
              class="greater-than"> &gt; </a>
          @endif
        </div>
        <img class="hidden loader" src="{{ asset('img/ajax-loader.gif') }}">
      </div>
    </div>
  </div>
  <div class="message_box"></div>
  <script type="text/javascript">
    var paginator = {
      current_page: {{ $users->getCurrentPage() }},
      last_page: {{ $users->getLastPage() }},
      score_type: '{{ $score_type }}',
      number_items: {{ $users->count() }},
      url: '{{ URL::to('/leaderboard') }}',
      user_page: {{ $user_page }},
    };
  </script>
  <script type="text/javascript" src={{ asset('/js/gamification/leaderboard.js') }}></script>
@stop
