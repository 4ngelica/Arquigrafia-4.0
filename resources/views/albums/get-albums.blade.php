  <h4>Selecione dentre seus álbuns ou crie um novo álbum para adicionar a imagem selecionada.</h4>
  <div class="tabs">
    <ul class="tab-links">
      <li class="active"><a class="tab-link" href="#your_albums">Seus álbuns</a></li>
      <li><a class="tab-link" href="#create_album">Novo álbum</a></li>
    </ul>
    <div class="tab-content">
      <div id="your_albums" class="tab active">
        <?php $album_counter = 0; $total_album = $albums->count() ?>
        {{ Form::open(array('url' => URL::to('/albums/photo/add'))) }}
          {{ Form::hidden('_photo', $photo_id) }}
          <div id="albums_list" class="list">
            <p class="row">Selecione um ou mais álbuns em que a imagem será adicionada.</p>
            <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
              <?php $album_counter = 0; ?>
              @foreach($albums as $album)
                @if ($album_counter % 3 == 0)
                  <tr>
                @endif
                <td width="33%">
                  <input type="checkbox"  id="{{ 'album_' . $album->id }}"
                    name="albums[]" class="albums" value="{{ $album->id }}">
                   <label for="{{ 'album_' . $album->id }}"></label>
                  <p>{{ $album->title }}</p>
                </td>
                @if($album_counter %3 == 2)
                  </tr>
                @endif
                <?php $album_counter++ ?>
              @endforeach

              @if($album_counter %3 > 0)
                @while($album_counter %3 > 0)
                  <td width="33%"></td>
                  <?php $album_counter++; ?>
                @endwhile
                </tr>
              @endif
            </table>
          </div>
          <p>{{ Form::submit("ADICIONAR", array('class'=>'btn')) }}</p>
        {{ Form::close() }}
      </div>
      <div id="create_album" class="tab row">
        <p class="row">
          Crie um novo álbum com a imagem que você selecionou.
        </p>
        <div id="info" class="seven columns">
        	{{ Form::open(array('url' => 'albums/')) }}
            {{ Form::hidden('photos_add', $photo_id) }}
            <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  <div class="img_container">
                    <img id="cover-img" src="{{ URL::to('/arquigrafia-images/' . $photo_id . '_view.jpg') }}">
                    <p>Capa do álbum</p>
                  </div>
                </td>
  	            <td>
  		            <div class="four columns"><p>{{ Form::label('title', 'Título*') }}</p></div>
  		            <div class="four columns row">
  		              <p>{{ Form::text('title', null) }} <br>
  		                <div class="error"></div>
  		              </p>
  		            </div>
  		            <div class="four columns"><p>{{ Form::label('description', 'Descrição') }}</p></div>
  		            <div class="four columns row">
  		              <p>{{ Form::textarea('description', null, array('rows' => 5)) }}</p>
  		            </div>
  		            <div class="four columns">
  		              <p>{{ Form::button('CRIAR ÁLBUM', array('class' => 'btn')) }}</p>
  		            </div>
  	            </td>
              </tr>
            </table>
          {{ Form::close() }}
        </div>
      </div>
    </div>
  <script type="text/javascript">
    var albums_list = $('#albums_list');
    var form1 = albums_list.parent();
    form1.submit(function(e) {
      var checked = albums_list.find('.albums:checked');
      if (checked.length > 0) {
        return true; //continua evento
      }
      form1.find('p.error').remove();
      var message = "Por favor, selecione um álbum existente ou crie um novo para adicionar a imagem selecionada.";
      form1.append('<p class="error">' + message + '</p>');
      e.preventDefault();
    });

    var form2 = $('#create_album form');
    form2.find('button').click(function (e) {
      form2.submit();
    });
    form2.submit( function(e) {
      var title = form2.find('#title');
      var div_error = form2.find('.error');
      div_error.empty();
      if ( !title.val() ) {
        div_error.text('O campo título é obrigatório');
        e.preventDefault();
      }
    });

    $('.tab-link').on('click', function(e) {
      e.preventDefault();
      var currentAttrValue = $(this).attr('href');
      $('.tabs ' + currentAttrValue).fadeIn('slow').siblings().hide();
      $(this).parent('li').addClass('active').siblings().removeClass('active');
    });
  </script>
  <style>
    .tab label { font-size: 16px; }
    .tab input[type="text"] { font-size: 14px; width: 200px; margin-bottom: 10px; /*color: #bbb;*/}
    .tab textarea { width: 300px; margin-bottom: 10px; font-size: 14px; }
    #create_album p { margin: 2px; color: #000; }
    #create_album #info { margin-top: 50px; }
    #create_album #info label { color: #777; }
    #create_album input, #create_album textarea { border-color: #aaa; }
    #create_album .img_container { width: 220px; border-radius: 3px; -moz-border-radius: 3px;
     -webkit-border-radius: 3px; position: relative; margin-top: 10px; min-height: 200px; }
    #create_album .img_container img { width: 100%; }
    @foreach($albums as $album)
      {{ '#album_' . $album->id . ' + label' }}
      {
        @if ($album->cover_id != null)
          background: url('{{ URL::to("/") . "/arquigrafia-images/" . $album->cover_id . "_home.jpg" }}');
        @else
          background: url('{{ URL::to("/") . "/img/registration_no_cover.png" }}');
        @endif
      }
    @endforeach
  </style>
