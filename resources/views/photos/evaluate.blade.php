@extends('layouts.default')

@section('head')

<title>Arquigrafia - {{ $photos->name }}</title>

<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/checkbox.css" />

<link rel="stylesheet" type="text/css" media="screen" href="{{ URL::to("/") }}/css/jquery.fancybox.css" />

<script type="text/javascript" src="{{ URL::to("/") }}/js/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/photo.js"></script>


@stop

	@section('content')

    @if (Session::get('message'))
      <div class="container">
        <div class="twelve columns">
          <div class="message">{!! Session::get('message') !!}</div>
        </div>
      </div>
    @endif

		<!--   MEIO DO SITE - ÁREA DE NAVEGAÇ?Ã?O   -->
		<div id="content" class="container">
			<!--   COLUNA ESQUERDA   -->
			<div class="eight columns">
				<!--   PAINEL DE VISUALIZACAO - SINGLE   -->
				<div id="single_view_block">
					<!--   NOME / STATUS DA FOTO   -->
					<div>
						<div class="four columns alpha">
            	<h1><a href="{{ URL::to("/search?q=".$photos->name)}}">
            {{ $photos->name }}
            </a></h1>


            </div>


			<div class="four columns omega">
              <span class="right" title="{{ $commentsMessage }}"><i id="comments"></i> <small>{{$commentsCount}}</small>
              </span>

            </div>
					</div>
					<!--   FIM - NOME / STATUS DA FOTO   -->

          <!--   FOTO   -->
					<a class="fancybox" href="{{ URL::to("/arquigrafia-images")."/".$photos->id."_view.jpg" }}" title="{{ $photos->name }}" ><img class="single_view_image" style="" src="{{ URL::to("/arquigrafia-images")."/".$photos->id."_view.jpg" }}" /></a>


				</div>

				<!--   BOX DE BOTOES DA IMAGEM   -->
				<div id="single_view_buttons_box">

					<?php if (Auth::check()) { ?>

	            <ul id="single_view_image_buttons">

							<li><a href="{{ URL::to('/albums/get/list/' . $photos->id) }}" title="Adicione aos seus álbuns" id="plus"></a></li>

							<li><a href="{{ asset('photos/download/'.$photos->id) }}" title="Faça o download" id="download" target="_blank"></a></li>

						</ul>

             <?php } else { ?>
              <div class="six columns alpha">Faça o login para fazer o download e comentar as imagens.</div>
            <?php } ?>

						<ul id="single_view_social_network_buttons">
						<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4fdf62121c50304d"></script>
							<li><a href="#" class="google addthis_button_google_plusone_share"><span class="google"></span></a></li>
							<li><a href="#" class="facebook addthis_button_facebook"><span class="facebook"></span></a></li>
							<li><a href="#" class="twitter addthis_button_twitter"><span class="twitter"></span></a></li>
						</ul>

				</div>
				<!--   FIM - BOX DE BOTOES DA IMAGEM   -->

        <div class="tags">
        	<h3>Tags:</h3>

					<p>

          <p>
          @if (isset($tags))
            @foreach($tags as $tag)
              @if ($tag->id == $tags->last()->id)
              <a style="" href="{{ URL::to("/search?q=".$tag->name) }}">
                {{ $tag->name }}   </a>
              @else
              <a href="{{ URL::to("/search?q=".$tag->name) }}">
                {{ $tag->name }}, </a>
              @endif
            @endforeach
          @endif

          </p>
          </div>

@if (!empty($average))
  <div id="evaluation_average">
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    <script type="text/javascript">
      $(function () {
        var l1 = [
            @foreach($binomials as $binomial)
              '{{ $binomial->firstOption}}',
            @endforeach
        ];
        var l2 = [
            @foreach($binomials as $binomial)
              '{{ $binomial->secondOption }}',
            @endforeach
        ];

        $('#evaluation_average').highcharts({
            credits: {
                enabled: false,
            },
            chart: {
                marginRight: 80,
            },
            title: {
                text: '<b> Média de interpretações d{{$architectureName}} </b>'
            },
            tooltip: {
              formatter: function() {
              return ''+ l1[this.y] + '-' + l2[this.y] + ': <br>' + this.series.name + '= ' + this.x;
              },
              crosshairs: [true,true]
            },
            xAxis: {
                lineColor: '#000',
                min: 0,
                max: 100,
            },
            yAxis: [{
                lineColor: '#000',
                lineWidth: 1,
                tickAmount: {{$binomials->count()}},
                tickPositions: [
                  <?php $count = 0?>
                  @foreach($binomials as $binomial)
                    {{ $count }},
                    <?php $count++; ?>
                  @endforeach
                ],
                title: {
                    text: ''
                },
                labels: {
                  formatter: function() {
                    return l1[this.value];
                  }
                }
            }, {
                lineWidth: 1,
                tickAmount: {{$binomials->count()}},
                tickPositions: [
                  <?php $count = 0?>
                  @foreach($binomials as $binomial)
                    {{ $count }},
                    <?php $count++; ?>
                  @endforeach
                ],
                opposite: true,
                title: {
                    text: ''
                },
                labels: {
                  formatter: function() {
                    return l2[this.value];
                  }
                },
            }],
            series: [{
                <?php $count = 0; ?>
                data: [
                  @foreach($average as $avg)
                    [{{ $avg->avgPosition }}, {{ $count }}],
                    <?php $count++ ?>
                  @endforeach
                ],
                yAxis: 1,
                name: 'Média',
                marker: {
                  symbol: 'circle',
                  enabled: true
                },
                color: '#999999',
            },

              {
                <?php $count = 0; ?>
                data: [
                  @if(isset($userEvaluations) && !$userEvaluations->isEmpty())
                    @foreach($userEvaluations as $userEvaluation)
                      [{{ $userEvaluation->evaluationPosition }}, {{ $count }}],
                      <?php $count++ ?>
                    @endforeach
                  @endif
                ],
                Axis: 0,
                marker: {
                  symbol: 'circle',
                  enabled: true
                },
                color: '#000000',
                name:
                <?php if (Auth::check() && $owner->id == Auth::user()->id && !Session::has('institutionId')){ ?>
                  'Suas impressões'
                <?php }else{ ?>
                  'Impressões de {{$owner->name}}'
                <?php } ?>
            }

            ]
        });
      });
    </script>
  </div>
@endif


		<!-- Photos with similar average  -->
    @if (count($similarPhotos) > 0)
    <div id="comments_block" class="eight columns row alpha omega">

    <hgroup class="profile_block_title">
      <h3><img src="{{ asset("img/evaluate.png") }}" width="16" height="16"/>
        Imagens interpretadas com média similar</h3>
        <span>({{count($similarPhotos) }})
            @if(count($similarPhotos)>1)
               Imagens
            @else
               Imagem
            @endif
        </span>
    </hgroup>

      @foreach($similarPhotos as $k => $similarPhoto)
         @if($photos->id != $similarPhoto->id)

                <a  class="hovertext" href='{{"/photos/" . $similarPhoto->id . "/showSimilarAverage/" }}' class="gallery_photo" title="{{ $similarPhoto->name }}">
                  <img src="{{ URL::to("/arquigrafia-images/" . $similarPhoto->id . "_home.jpg") }}" class="gallery_photo" />
                </a>
                <!--
                <a href='{{"/photos/" . $similarPhoto->id . "/evaluate" }}' class="name">
                  <div class="innerbox">{{ $similarPhoto->name }}</div>
                </a>-->

          @endif
      @endforeach


    </div>
     @endif


			</div>
			<!--   FIM - COLUNA ESQUERDA   -->
			<!--   SIDEBAR   -->

			<div id="sidebar" class="four columns">

				<!--   USUARIO   -->

				<!--   FIM - USUARIO   -->

        <!-- AVALIAÇÃO  Suas impressões-->
        @if($owner->equal(Auth::user()) &&  !Session::has('institutionId'))
        <h3>Suas impressões d{{$architectureName}}</h3>
        @else
        <h3>Interpretação d{{$architectureName}} realizada por <a href="{{ URL::to("/users/".$owner->id) }}" id="name">{{$owner->name}}</a></h3>
        @endif
	       <br>
         {{ Form::open(array('url' => "photos/{$photos->id}/saveEvaluation")) }}

         @if (Auth::check() && $owner != null && $owner->id == Auth::user()->id && !Session::has('institutionId'))
         {{ Form::checkbox('knownArchitecture', 'yes', $checkedKnowArchitecture) }}
         @else
         {{ Form::checkbox('knownArchitecture', 'yes', $checkedKnowArchitecture, ['disabled']) }}
         @endif

         Eu conheço pessoalmente esta arquitetura.

	       <br><br>
         @if (Auth::check() && $owner != null && $owner->id == Auth::user()->id && !Session::has('institutionId'))
         {{ Form::checkbox('areArchitecture', 'yes', $checkedAreArchitecture) }}
         @else
         {{ Form::checkbox('areArchitecture', 'yes', $checkedAreArchitecture, ['disabled']) }}
         @endif


         Estou no local.

         <br><br>

        <p>Para cada um dos pares abaixo, quais são as qualidades predominantes na arquitetura que são visíveis nesta imagem?</p>

        <!-- FORMULÁRIO DE AVALIAÇÃO -->
        <div id="evaluation_box">

          <?php if (Auth::check()) { ?>
            <script>
              var baseURL = '{{ URL::to('/search') }}';
              function outputUpdate(binomio, val) {
                var left, right;
                left = document.querySelector('#leftBinomialValue'+binomio);
                right = document.querySelector('#rightBinomialValue'+binomio);
                left.value = 100 - val;
                right.value = val;
                makeSearchURL(binomio, val);
              }
              function makeSearchURL(binomio, val) {
                var left = document.querySelector('.output#first_' + binomio);
                var right = document.querySelector('.output#second_' + binomio);
                left.href = baseURL + '/?bin=' + binomio + '&opt=1&val=' + val;
                right.href = baseURL + '/?bin=' + binomio + '&opt=2&val=' + val;
              }
            </script>
            <?php $count = $binomials->count() - 1; ?>
            @foreach($binomials->reverse() as $binomial)
              <?php
                if ( isset($userEvaluations) && ! $userEvaluations->isEmpty() ) {
                  $userEvaluation = $userEvaluations->get($count);
                  $diff = $userEvaluation->evaluationPosition;
                } else {
                  $diff = $binomial->defaultValue;
                }
              ?>
              <p>
                <table border="0" width="230">
                  <tr>
                    <td width="110">
                      <a href="{{ URL::to('/search?bin=' . $binomial->id . '&opt=1') }}">
                        {{ $binomial->firstOption }}
                      </a>
                      <a class="output" id="first_{{ $binomial->id }}"
                        href="{{ URL::to('/search?bin=' . $binomial->id . '&opt=1&val=' . $diff) }}">
                        (<output for="fader{{ $binomial->id }}"
                          id="leftBinomialValue{{ $binomial->id }}">
                          {{100 - $diff }}
                        </output>%)
                      </a>
                    </td>
                    <td align="right">
                      <a href="{{ URL::to('/search?bin=' . $binomial->id . '&opt=2') }}">
                        {{ $binomial->secondOption }}
                      </a>
                      <a class="output" id="second_{{ $binomial->id }}"
                        href="{{ URL::to('/search?bin=' . $binomial->id . '&opt=2&val=' . $diff) }}">
                        (<output for="fader{{ $binomial->id }}"
                          id="rightBinomialValue{{ $binomial->id }}">
                          {{ $diff }}
                        </output>%)
                      </a>
                    </td>
                  </tr>
                </table>
                @if (Auth::check() && $owner != null && $owner->id == Auth::user()->id && !Session::has('institutionId'))
                {{ Form::input('range', 'value-'.$binomial->id, $diff,
                  [ 'min' => '0',
                    'max' => '100',
                    'oninput' => 'outputUpdate(' . $binomial->id . ', value)' ])
                }}
                @else
                {{ Form::input('range', 'value-'.$binomial->id, $diff,
                  [ 'min' => '0',
                    'max' => '100',
                    'oninput' => 'outputUpdate(' . $binomial->id . ', value)',
                    'disabled' ])
                }}
                @endif
              </p>
              <?php $count-- ?>
            @endforeach

               <a href="{{ URL::to('/photos/' . $photos->id) }}" class='btn right'>VOLTAR</a>
               @if (Auth::check() && $owner != null && $owner->id == Auth::user()->id && !Session::has('institutionId'))
                {{ Form::submit('ENVIAR', ['id'=>'evaluation_button','class'=>'btn right']) }}
               @endif

            {{ Form::close() }}


          <?php } else { ?>
            @if (empty($average))
              <p>Faça o <a href="{{ URL::to('/users/login') }}">Login</a> e seja o primeiro a registrar impressões sobre {{$architectureName}}</p>
            @else
              <p>Faça o <a href="{{ URL::to('/users/login') }}">Login</a> e registre você também impressões sobre {{$architectureName}}</p>
            @endif
          <?php } ?>


   @if (Auth::check())
     @if (isset($userEvaluations) && !$userEvaluations->isEmpty() && !Session::get('institutionId'))
	@if($owner != null && $owner->id != Auth::user()->id )
            <a href='{{"/photos/" . $photos->id . "/evaluate?f=c" }}' title="Interpretar" id="evaluate_button"
            class="btn">
              Registre você também impressões sobre {{$architectureName}}
            </a> &nbsp;
          @endif
        @endif
     @endif

        </div>


      </br>
    </br>
			<!--   FIM - SIDEBAR   -->
		</div>

		<!--   MODAL   -->
		<div id="mask"></div>
		<div id="form_window" class="form window">
			<!-- ÁREA DE LOGIN - JANELA MODAL -->
			<a class="close" href="#" title="FECHAR">Fechar</a>
			<div id="registration"></div>
		</div>
		<div id="confirmation_window" class="window">
			<div id="registration_delete">
				<p></p>
				{{ Form::open(array('url' => '', 'method' => 'delete')) }}
					<div id="registration_buttons">
						<input type="submit" class="btn" value="Confirmar" />
						<a class="btn close" href="#">Cancelar</a>
					</div>
				{{ Form::close() }}
			</div>
		</div>

@stop
