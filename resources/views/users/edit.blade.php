@extends('layouts.default')

@section('head')
  <title>Arquigrafia - Seu universo de imagens de arquitetura</title>

  <script type="text/javascript" src="{{ URL::to("/") }}/js/textext.js"></script>
  <link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.css" />

  <link rel="stylesheet" href="{{ URL::to("/") }}/css/jquery-ui/jquery-ui.min.css">
  <script type="text/javascript" src="{{ URL::to("/") }}/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
@stop

@section('content')

  <div class="container">
    {{ Form::open(array('url'=>'users/' . $user->id, 'method' => 'put', 'files'=> true, 'id'=>'frmEdit')) }}

    <div id="registration">

      <div class="twelve columns">
        <h1>Edição de Perfil do usuário</h1>
        <p>
        <div>
            <?php if ($user->photo != "") { ?>
              <img class="avatar profile-picture" src="{{ asset($user->photo) }}" class="user_photo_thumbnail" width="60" height="60" />
            <?php } else { ?>
              <img class="avatar profile-picture" src="{{ asset("img/avatar-60.png") }}" width="60" height="60" class="user_photo_thumbnail"/>
            <?php } ?>
          </div>
        </p>
      </div>
      <p>
      </p>

      <div class="four columns">

        <div class="twelve columns row step-1">


        <div class="four columns alpha">
          <p>{{ Form::label('photo','Alterar foto:') }}
          @if($user->id_facebook != null)
            <a id="btn_facebook" class="btn import-face" onclick="importPicture()">
              <img class="facebook-logo" src="{{ asset('/img/Facebook_logo_square.png') }}">
              <span>Importar foto do facebook</span>
            </a>
          @endif
          {{ Form::file('photo', array('id'=>'imageUpload', 'onchange' => 'readURL(this);')) }}</p>
          <br>
          <img src="" id="preview_photo">
          <br>
        </div>
      </div>
      <br>
      <div class="error">{{ $errors->first('photo') }}</div>
      <br>
      <script type="text/javascript">
        function importPicture() {
          $.get("/getPicture")
            .done(function( data ) {
               {  var elementExists = document.getElementById("txtPicture");
                  if(elementExists == null){
                      $('<input/>').attr({
                      type:'hidden',
                      name:'txtPicture',
                      id:'txtPicture',
                      value:'picture',
                      readonly:'true' //,
                      // disabled: 'true'
                      }).appendTo('#frmEdit');
                  }
                  $('.profile-picture').attr('src', data);
                }
            });
        }
      </script>

     <script type="text/javascript">
        function readURL(input) {
          $("#preview_photo").hide();
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              $('#preview_photo')
                .attr('src', e.target.result)
                .width(200);
                $("#preview_photo").show();
            };
            reader.readAsDataURL(input.files[0]);
          }
        }
      </script>



        {{ Form::open(array('url' => 'users')) }}

          <p>(*) Campos obrigatórios.</p>
          <br />

          <div class="two columns alpha"><p>{{ Form::label('name', 'Nome completo*:') }}</p></div>
          <div class="two columns omega">
            <p>{{Form::text('name', $user->name)}} <br>
            <div class="error">{{ $errors->first('name') }} </div>
            </p>
          </div>

          @if($user->login != $user->id_facebook)
            @if($user->login != $user->id_stoa)
              <div class="two columns alpha"><p>{{ Form::label('login', 'Login*:') }}</p></div>
              <div class="two columns omega">
                <p>{{ Form::text('login', $user->login) }}<br>
                <div class="error">{{ $errors->first('login') }} </div>
                </p>
              </div>
            @endif
          @endif

          <div class="two columns alpha"><p>{{ Form::label('email', 'E-mail*:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('email', $user->email) }} <br>
              <div class="error">{{ $errors->first('email') }} </div>
            <input name="visibleEmail" type="checkbox" value="yes" {{$user->visibleEmail == 'yes' ? "checked" : null}}>Visível no perfil público<br>
            </p>
          </div>

          <!-- Alterar tabela users no bd: trocar campo lastName para nickname -->
          <div class="two columns alpha"><p>{{ Form::label('lastName', 'Apelido:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('lastName', $user->lastName) }} <br>
            <div class="error">{{ $errors->first('lastName') }} </div>
            </p>
          </div>


         <div class="two columns alpha"><p>{{ Form::label('birthday', 'Data de nascimento:') }}</p></div>

         <div class="two columns omega">
         @if (($user->birthday)!= null )
          <p>{{ Form::text('birthday',date("d/m/Y",strtotime($user->birthday)),array('id' => 'datePickerBirthday','placeholder'=>'dd/mm/yyyy')) }}
         @else
          <p>{{ Form::text('birthday',null,array('id' => 'datePickerBirthday','placeholder'=>'dd/mm/yyyy')) }}
         @endif
         <input name="visibleBirthday" type="checkbox" value="yes" {{$user->visibleBirthday == 'yes' ? "checked" : null}}>Visível no perfil público <br>
         <div class="error">{{ $errors->first('birthday') }}</div>
         </p>
        </div>


          <div class="two columns alpha" >
            <p>Sexo: </p>
          </div>
          <div class="two columns omega">
              <input type="radio" name="gender" value="female" id="gender" {{$user->gender == 'female' ? "checked" : null}}>
              <label for="gender">Feminino</label><br class="clear">
              <input type="radio" name="gender" value="male" id="gender" {{$user->gender == 'male' ? "checked" : null}}>
              <label for="gender">Masculino</label><br class="clear">
          </div>

        <div class="two columns alpha"><p>{{ Form::label('country', 'País:') }}</p></div>
        <div class="two columns omega">
          <p>{{ Form::select('country', [ "Afeganistão"=>"Afeganistão", "África do Sul"=>"África do Sul", "Albânia"=>"Albânia", "Alemanha"=>"Alemanha", "América Samoa"=>"América Samoa", "Andorra"=>"Andorra", "Angola"=>"Angola", "Anguilla"=>"Anguilla", "Antartida"=>"Antartida", "Antigua"=>"Antigua", "Antigua e Barbuda"=>"Antigua e Barbuda", "Arábia Saudita"=>"Arábia Saudita", "Argentina"=>"Argentina", "Aruba"=>"Aruba", "Australia"=>"Australia", "Austria"=>"Austria", "Bahamas"=>"Bahamas", "Bahrain"=>"Bahrain", "Barbados"=>"Barbados", "Bélgica"=>"Bélgica", "Belize"=>"Belize", "Bermuda"=>"Bermuda", "Bhutan"=>"Bhutan", "Bolívia"=>"Bolívia", "Botswana"=>"Botswana", "Brasil"=>"Brasil", "Brunei"=>"Brunei", "Bulgária"=>"Bulgária", "Burundi"=>"Burundi", "Cabo Verde"=>"Cabo Verde", "Camboja"=>"Camboja", "Canadá"=>"Canadá", "Chade"=>"Chade", "Chile"=>"Chile", "China"=>"China", "Cingapura"=>"Cingapura", "Colômbia"=>"Colômbia", "Djibouti"=>"Djibouti", "Dominicana"=>"Dominicana", "Emirados Árabes"=>"Emirados Árabes", "Equador"=>"Equador", "Espanha"=>"Espanha", "Estados Unidos"=>"Estados Unidos", "Fiji"=>"Fiji", "Filipinas"=>"Filipinas", "Finlândia"=>"Finlândia", "França"=>"França", "Gabão"=>"Gabão", "Gaza Strip"=>"Gaza Strip", "Ghana"=>"Ghana", "Gibraltar"=>"Gibraltar", "Granada"=>"Granada", "Grécia"=>"Grécia", "Guadalupe"=>"Guadalupe", "Guam"=>"Guam", "Guatemala"=>"Guatemala", "Guernsey"=>"Guernsey", "Guiana"=>"Guiana", "Guiana Francesa"=>"Guiana Francesa", "Haiti"=>"Haiti", "Holanda"=>"Holanda", "Honduras"=>"Honduras", "Hong Kong"=>"Hong Kong", "Hungria"=>"Hungria", "Ilha Cocos (Keeling)"=>"Ilha Cocos (Keeling)", "Ilha Cook"=>"Ilha Cook", "Ilha Marshall"=>"Ilha Marshall", "Ilha Norfolk"=>"Ilha Norfolk", "Ilhas Turcas e Caicos"=>"Ilhas Turcas e Caicos", "Ilhas Virgens"=>"Ilhas Virgens", "Índia"=>"Índia", "Indonésia"=>"Indonésia", "Inglaterra"=>"Inglaterra", "Irã"=>"Irã", "Iraque"=>"Iraque", "Irlanda"=>"Irlanda", "Irlanda do Norte"=>"Irlanda do Norte", "Islândia"=>"Islândia", "Israel"=>"Israel", "Itália"=>"Itália", "Iugoslávia"=>"Iugoslávia", "Jamaica"=>"Jamaica", "Japão"=>"Japão", "Jersey"=>"Jersey", "Kirgizstão"=>"Kirgizstão", "Kiribati"=>"Kiribati", "Kittsnev"=>"Kittsnev", "Kuwait"=>"Kuwait", "Laos"=>"Laos", "Lesotho"=>"Lesotho", "Líbano"=>"Líbano", "Líbia"=>"Líbia", "Liechtenstein"=>"Liechtenstein", "Luxemburgo"=>"Luxemburgo", "Maldivas"=>"Maldivas", "Malta"=>"Malta", "Marrocos"=>"Marrocos", "Mauritânia"=>"Mauritânia", "Mauritius"=>"Mauritius", "México"=>"México", "Moçambique"=>"Moçambique", "Mônaco"=>"Mônaco", "Mongólia"=>"Mongólia", "Namíbia"=>"Namíbia", "Nepal"=>"Nepal", "Netherlands Antilles"=>"Netherlands Antilles", "Nicarágua"=>"Nicarágua", "Nigéria"=>"Nigéria", "Noruega"=>"Noruega", "Nova Zelândia"=>"Nova Zelândia", "Omã"=>"Omã", "Panamá"=>"Panamá", "Paquistão"=>"Paquistão", "Paraguai"=>"Paraguai", "Peru"=>"Peru", "Polinésia Francesa"=>"Polinésia Francesa", "Polônia"=>"Polônia", "Portugal"=>"Portugal", "Qatar"=>"Qatar", "Quênia"=>"Quênia", "República Dominicana"=>"República Dominicana", "Romênia"=>"Romênia", "Rússia"=>"Rússia", "Santa Helena"=>"Santa Helena", "Santa Kitts e Nevis"=>"Santa Kitts e Nevis", "Santa Lúcia"=>"Santa Lúcia", "São Vicente"=>"São Vicente", "Singapura"=>"Singapura", "Síria"=>"Síria", "Spiemich"=>"Spiemich", "Sudão"=>"Sudão", "Suécia"=>"Suécia", "Suiça"=>"Suiça", "Suriname"=>"Suriname", "Swaziland"=>"Swaziland", "Tailândia"=>"Tailândia", "Taiwan"=>"Taiwan", "Tchecoslováquia"=>"Tchecoslováquia", "Tonga"=>"Tonga", "Trinidad e Tobago"=>"Trinidad e Tobago", "Turksccai"=>"Turksccai", "Turquia"=>"Turquia", "Tuvalu"=>"Tuvalu", "Uruguai"=>"Uruguai", "Vanuatu"=>"Vanuatu", "Wallis e Fortuna"=>"Wallis e Fortuna", "West Bank"=>"West Bank", "Yémen"=>"Yémen", "Zaire"=>"Zaire", "Zimbabwe"=>"Zimbabwe"], $user->country != null ? $user->country : "Brasil") }}<br>
            <div class="error">{{ $errors->first('country') }} </div>
          </p>
        </div>

        <div class="two columns alpha"><p>{{ Form::label('state', 'Estado:') }}</p></div>
        <div class="two columns omega">
          <p>{{ Form::select('state', [""=>"Escolha o Estado", "AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá", "BA"=>"Bahia", "CE"=>"Ceará", "DF"=>"Distrito Federal", "ES"=>"Espirito Santo", "GO"=>"Goiás", "MA"=>"Maranhão", "MG"=>"Minas Gerais", "MS"=>"Mato Grosso do Sul", "MT"=>"Mato Grosso", "PA"=>"Pará", "PB"=>"Paraíba", "PE"=>"Pernambuco", "PI"=>"Piauí", "PR"=>"Paraná", "RJ"=>"Rio de Janeiro", "RN"=>"Rio Grande do Norte", "RO"=>"Rondônia", "RR"=>"Roraima", "RS"=>"Rio Grande do Sul", "SC"=>"Santa Catarina", "SE"=>"Sergipe", "SP"=>"São Paulo", "TO"=>"Tocantins"], $user->state) }} <br>
            <div class="error">{{ $errors->first('state') }} </div>
          </p>
        </div>

        <div class="two columns alpha"><p>{{ Form::label('city', 'Cidade:') }}</p></div>
        <div class="two columns omega">
        <p>{{ Form::text('city', $user->city) }}<br>
          <div class="error">{{ $errors->first('city') }} </div>
        </p>
        </div>

          <div class="two columns alpha"><p>{{ Form::label('scholarity', 'Escolaridade:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('scholarity', $user->scholarity) }} <br>
              <div class="error">{{ $errors->first('scholarity') }} </div>
            </p>
          </div>

          <div class="two columns alpha"><p>{{ Form::label('institution', 'Instituição:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('institution', $user->institution != null ? $user->occupation->institution : null) }} <br>
              <div class="error">{{ $errors->first('institution') }} </div>
            </p>
          </div>

          <div class="two columns alpha"><p>{{ Form::label('occupation', 'Profissão:') }}</p></div>
          <div class="two columns omega">
            <p>{{ Form::text('occupation', $user->occupation != null && $user->occupation->occupation != null ? $user->occupation->occupation : null) }} <br>
              <div class="error">{{ $errors->first('occupation') }} </div>
            </p>
          </div>

          <div class="two columns row alpha"><p>{{ Form::label('site', 'Site pessoal:') }}</p></div>
          <div class="two columns row omega">
            <p>{{ Form::text('site', $user->site) }}<br>
              <div class="error">{{ $errors->first('site') }} </div>
            </p>
          </div>
          <!--
          <div class="five columns alpha">
              <br>
              <span class="txt_stl_pass" style = "" >Mudar senha?
                  <a onclick="password_change('passChange');" >Clique aqui.</a>
              </span>
          </div>
         <div id="passChange" style="display:none;">
              <br>
              <p>&nbsp;</p>
              <p>&nbsp;</p>-->
              <div class="two columns alpha"><p>{{ Form::label('old_password', 'Antiga senha :') }}</p></div>
              <div class="two columns omega">
                <p>{{ Form::password('old_password') }}
                  <div class="error">{{ $errors->first('old_password') }}</div>
                </p>
              </div>
              <div class="two columns alpha"><p>{{ Form::label('user_password', 'Nova senha:') }}</p></div>
              <div class="two columns omega">
                <p>{{ Form::password('user_password') }}<br>
                <div class="error">{{ $errors->first('user_password') }} </div>
                </p>
              </div>
              <div class="two columns alpha"><p>{{ Form::label('password_confirmation', 'Repita a senha:') }}</p></div>
              <div class="two columns omega"><p>{{ Form::password('user_password_confirmation') }}</p></div>
         <!--  </div> -->

          <div class="four columns alpha omega">
            <br>
            <a href="{{ URL::to('/users/' . $user->id) }}" class='btn right'>VOLTAR</a>
            {{ Form::submit("EDITAR", array('class'=>'btn right')) }}

          </div>


        {{ Form::close() }}

        <p>&nbsp;</p>

      </div>

    </div>

  </div>
<script type="text/javascript">
//msy
    $(function() {

    $( "#datePickerBirthday" ).datepicker({
      dateFormat:'dd/mm/yy'
    }
      );
    });

    function password_change(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'none'){
          e.style.display = 'block';
       }else{
          e.style.display = 'none';
        }


}
</script>
@stop
