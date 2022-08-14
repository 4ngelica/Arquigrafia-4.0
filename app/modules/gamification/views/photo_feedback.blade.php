  @if ( $owner->equal(Auth::user()) )
    <div id="image_info_completion">
      <h4>Completude das informações:</h4>
      <div id="progressbar"></div>
      @if ($photos->information_completion < 100)
        <p>
          <a id="improve_image_data" href="{{ URL::to('/photos/' . $photos->id . '/edit') }}">Deseja melhorar as informações da imagem?</a>
        </p>
        <div id="information_input" class="four columns alpha omega row" >
          <p><a href="#" class="close">X</a></p>
          {{ Form::open( array('url' => 'photos/' . $photos->id . '/set/field') ) }}
            <div class="four columns alpha omega">
              <h3>{{ $question }}</h3>
              {{ Form::hidden('field', $field) }}
              @if ($field == "description")
                <textarea name="description"></textarea>
              @else
                {{ Form::text($field, null) }}
              @endif
            </div>
            <input type="submit" class="btn" value="SALVAR">
            <button class="btn" id="skip_question">PULAR</button>
          {{ Form::close() }}
          <img class="loader" src="{{ URL::to('/img/ajax-loader.gif') }}" />
        </div>
      @endif
    </div>
  @endif
  <script type="text/javascript" src="{{ URL::to("/") }}/js/progressbar.js"></script>
  <script type="text/javascript">
    var getFieldURL = "{{ $getFieldURL }}";
    var setFieldURL = "{{ $setFieldURL }}";
    var currentField = 1;
    var hasField = true;
    var progressbar = $('#progressbar').progressbar();
    progressbar.progress( {{ $photos->information_completion }} );
  </script>