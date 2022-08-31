@extends('layouts.default')

@section('head')

<title>Arquigrafia - Fotos - Update</title>


<!--<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.css" />-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.plugin.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.plugin.tags.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/styletags.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.core.css" />



<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.js"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.core.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.tags.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.autocomplete.js" charset="utf-8"></script>

<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.ajax.js" charset="utf-8"></script>

<script type="text/javascript" src="{{ URL::to("/") }}/js/tags-autocomplete.js" charset="utf-8"></script>

<script type="text/javascript" src="{{ URL::to("/") }}/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/tag-list.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/tag-autocomplete-part.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/city-autocomplete.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/date-work.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/rotate.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/readURL.js" charset="utf-8"></script>

<style>
  .ui-autocomplete {
    max-height: 100px;
    font-size: 12px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>

@stop

@section('content')

  <div class="container">
  <div id="registration">
      {{ Form::open(array('url'=>'institutions/'.$photo->id.'/update/photo', 'method' => 'put', 'files'=> true)) }}

      <div class="twelve columns row step-1">
        <h1><span class="step-text">Edição de informações {{$photo->name}}</span></h1>
            <div class="eleven columns alpha" id="media_type">
              <br>
              <div class="form-row">
                <input type="radio" name="type" value="photo" id="type_photo" {{$photo->type == 'photo' ? 'checked="checked"' : ""}}
                  {{$photo->type == NULL ? 'checked="checked"' : ""}} >
                <label for="type_photo">Foto</label><br class="clear">
              </div>
              <div class="form-row">
                <input type="radio" name="type" value="video" id="type_video" {{$photo->type == 'video' ? "checked" : ""}}>
                <label for="type_video">Vídeo</label><br class="clear" >
              </div>
            </div>

        <div id="divPhoto" class="four columns alpha">
            <a class="fancybox" href="{{ URL::to("/arquigrafia-images")."/".$photo->id."_view.jpg" }}" >
            <img id="old_image" class="single_view_image" style="" src="{{ URL::to("/arquigrafia-images")."/".$photo->id."_view.jpg" }}" />
            </a>
            <div id="old_image_rotate">
              <br></br>
              <a class="btn left" onclick="Rotate(document.getElementById('old_image'), -Math.PI/2);">Girar 90° para esquerda</a>
              <a class="btn right" onclick="Rotate(document.getElementById('old_image'), Math.PI/2);">Girar 90° para direita</a>
            </div>
            <br></br>
            <img src="" id="preview_photo">
            <div id="image_rotate" style="display:none;">
              <br></br>
              <a class="btn left" onclick="Rotate(document.getElementById('preview_photo'), -Math.PI/2);">Girar 90° para esquerda</a>
              <a class="btn right" onclick="Rotate(document.getElementById('preview_photo'), Math.PI/2);">Girar 90° para direita</a>
            </div>

            <p>
              {{ Form::label('photo','Alterar imagem:') }}
              {{ Form::file('photo', array('id'=>'imageUpload', 'onchange' => 'readURL(this);')) }}
              <br></br>
              <div class="error">{{ $errors->first('photo') }}</div>
            </p>
        </div>

        <div id="divVideo" class="twelve columns alpha">
        </br>
        <div class="two columns alpha">{{ Form::label('video', 'Link do vídeo youtube ou vimeo:') }}</div>
          <div class="four columns alpha">
            <p>{{ Form::text('video', $video, array('id' => 'video','style'=>'width:280px')) }} <br>
             </p>
             <div class="error">{{ $errors->first('video') }}</div>
             <p>Ex. https://www.youtube.com/watch?v=XXXXXXXX ou <br>
                ou  https://vimeo.com/XXXXXXXX</p>
          </div>
        </div>

      </div>


      <div id="registration" class="twelve columns row step-2">


          <h4>Campos obrigatórios (*)</h4>
          <br class="clear">
         <!-- <h4>Campos complementares</h4>-->

          <div class="eight columns alpha row">
            <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('support', 'Suporte*:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>{{ Form::text('support', $photo->support) }} <br>
                      <div class="error">{{ $errors->first('support') }}</div>
                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('tomboTxt', 'Tombo*:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>{{ Form::text('tombo', $photo->tombo ) }} <br>
                      <div class="error">{{ $errors->first('tombo') }}</div>
                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('subjectTxt', 'Assunto:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>{{ Form::text('subject', $photo->subject) }} <br>
                      <div class="error">{{ $errors->first('subject') }}</div>
                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('hygieneDateTxt', 'Data de higienização:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>
                    @if (($photo->hygieneDate)!= null )
                      {{ Form::text('hygieneDate',date("d/m/Y",strtotime($photo->hygieneDate)),array('id' => 'datePickerHygieneDate','placeholder'=>'DD/MM/AAAA')) }}
                    @else
                      {{ Form::text('hygieneDate','',array('id' => 'datePickerHygieneDate','placeholder'=>'DD/MM/AAAA')) }}
                    @endif
                      <br>
                      <div class="error">{{ $errors->first('hygieneDate') }}</div>
                    </p>

                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('backupDateTxt', 'Data de backup:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>
                      @if (($photo->backupDate)!= null )
                      {{ Form::text('backupDate',date("d/m/Y",strtotime($photo->backupDate)),array('id' => 'datePickerBackupDate','placeholder'=>'DD/MM/AAAA')) }}
                      @else
                      {{ Form::text('backupDate','',array('id' => 'datePickerBackupDate','placeholder'=>'DD/MM/AAAA')) }}
                      @endif
                      <br>
                      <div class="error">{{ $errors->first('backupDate') }}</div>
                    </p>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('characterizationTxt', 'Caracterização*:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>{{ Form::text('characterization',$photo->characterization ) }} <br>
                      <div class="error">{{ $errors->first('characterization') }}</div>
                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('userResponsibleTxt', 'Usuário Responsável:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>{{ Form::text('userResponsible', $user->name,['readonly']) }} <br>
                      <div class="error">{{ $errors->first('userResponsible') }}</div>
                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('name', 'Título*:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>{{ Form::text('photo_name', $photo->name) }} <br>
                      <div class="error">{{ $errors->first('photo_name') }}</div>
                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha">
                    <p>{{ Form::label('description', 'Descrição:') }}</p>
                  </div>
                  <div class="three columns omega">
                    <p>

                      {{ Form::textarea('description', $photo->description) }}<br>

                    </p>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="two columns alpha"><p>{{ Form::label('tags_input', 'Tags*:') }}</p></div>
                  <div class="three columns">
                    <p><div style="max-width:180px;">
                      {{ Form::text('tags_input',null,array('id' => 'tags_input','style'=>'width: 200px; height:15px; border:solid 1px #ccc')) }}
                       </div>
                      <br>
                      <div class="error">{{ $errors->first('tagsArea') }}</div>
                    </p>
                  </div>
                  <div>
                    <button class="btn" id="add_tag" style="font-size: 11px;">ADICIONAR TAG</button>
                  </div>
                  <div class="five columns alpha">
                    <textarea name="tagsArea" id="tagsArea" cols="79" rows="2" style="display: none;">
                    </textarea>
                  </div>
                </td>
              </tr>

              <tr><td><br class="clear"></td></tr>
              <tr><td>
                @include('photos.includes.workAuthorInst')
              </td>
            </tr>
              <tr><td></td></tr>
              <tr> <td>
                <div class="two columns alpha"><p>{{ Form::label('workDate', 'Ano de conclusão da obra:') }}</p></div>
                 <div class="six columns omega">
                  <p>
                      @include('photos.includes.dateList')
                      <span class="space_txt_element"> Não sabe a data precisa?
                      <a  onclick="date_visibility('otherDate');" >Clique aqui.</a> </span>
                  </p>
                  <p>
                    <div id="otherDate" class="div_institutional_edit" style="display:none;">
                         @include('photos.includes.dateWork')
                      </div>
                    <label id="answer_date" class="resultDateWork"></label>
                  </p>

                </div>
              </td>
            </tr>
            </table>
          </div>
          <br class="clear">
          <div class="eight columns alpha row">
            <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr><td>
                <div class="two columns alpha"><p>{{ Form::label('country', 'País*:') }}</p></div>
                <div class="two columns omega">
                    <p>{{ Form::select('country', [ "Afeganistão"=>"Afeganistão", "África do Sul"=>"África do Sul", "Albânia"=>"Albânia", "Alemanha"=>"Alemanha", "América Samoa"=>"América Samoa", "Andorra"=>"Andorra", "Angola"=>"Angola", "Anguilla"=>"Anguilla", "Antartida"=>"Antartida", "Antigua"=>"Antigua", "Antigua e Barbuda"=>"Antigua e Barbuda", "Arábia Saudita"=>"Arábia Saudita", "Argentina"=>"Argentina", "Aruba"=>"Aruba", "Australia"=>"Australia", "Austria"=>"Austria", "Bahamas"=>"Bahamas", "Bahrain"=>"Bahrain", "Barbados"=>"Barbados", "Bélgica"=>"Bélgica", "Belize"=>"Belize", "Bermuda"=>"Bermuda", "Bhutan"=>"Bhutan", "Bolívia"=>"Bolívia", "Botswana"=>"Botswana", "Brasil"=>"Brasil", "Brunei"=>"Brunei", "Bulgária"=>"Bulgária", "Burundi"=>"Burundi", "Cabo Verde"=>"Cabo Verde", "Camboja"=>"Camboja", "Canadá"=>"Canadá", "Chade"=>"Chade", "Chile"=>"Chile", "China"=>"China", "Cingapura"=>"Cingapura", "Colômbia"=>"Colômbia", "Djibouti"=>"Djibouti", "Dominicana"=>"Dominicana", "Emirados Árabes"=>"Emirados Árabes", "Equador"=>"Equador", "Espanha"=>"Espanha", "Estados Unidos"=>"Estados Unidos", "Fiji"=>"Fiji", "Filipinas"=>"Filipinas", "Finlândia"=>"Finlândia", "França"=>"França", "Gabão"=>"Gabão", "Gaza Strip"=>"Gaza Strip", "Ghana"=>"Ghana", "Gibraltar"=>"Gibraltar", "Granada"=>"Granada", "Grécia"=>"Grécia", "Guadalupe"=>"Guadalupe", "Guam"=>"Guam", "Guatemala"=>"Guatemala", "Guernsey"=>"Guernsey", "Guiana"=>"Guiana", "Guiana Francesa"=>"Guiana Francesa", "Haiti"=>"Haiti", "Holanda"=>"Holanda", "Honduras"=>"Honduras", "Hong Kong"=>"Hong Kong", "Hungria"=>"Hungria", "Ilha Cocos (Keeling)"=>"Ilha Cocos (Keeling)", "Ilha Cook"=>"Ilha Cook", "Ilha Marshall"=>"Ilha Marshall", "Ilha Norfolk"=>"Ilha Norfolk", "Ilhas Turcas e Caicos"=>"Ilhas Turcas e Caicos", "Ilhas Virgens"=>"Ilhas Virgens", "Índia"=>"Índia", "Indonésia"=>"Indonésia", "Inglaterra"=>"Inglaterra", "Irã"=>"Irã", "Iraque"=>"Iraque", "Irlanda"=>"Irlanda", "Irlanda do Norte"=>"Irlanda do Norte", "Islândia"=>"Islândia", "Israel"=>"Israel", "Itália"=>"Itália", "Iugoslávia"=>"Iugoslávia", "Jamaica"=>"Jamaica", "Japão"=>"Japão", "Jersey"=>"Jersey", "Kirgizstão"=>"Kirgizstão", "Kiribati"=>"Kiribati", "Kittsnev"=>"Kittsnev", "Kuwait"=>"Kuwait", "Laos"=>"Laos", "Lesotho"=>"Lesotho", "Líbano"=>"Líbano", "Líbia"=>"Líbia", "Liechtenstein"=>"Liechtenstein", "Luxemburgo"=>"Luxemburgo", "Maldivas"=>"Maldivas", "Malta"=>"Malta", "Marrocos"=>"Marrocos", "Mauritânia"=>"Mauritânia", "Mauritius"=>"Mauritius", "México"=>"México", "Moçambique"=>"Moçambique", "Mônaco"=>"Mônaco", "Mongólia"=>"Mongólia", "Namíbia"=>"Namíbia", "Nepal"=>"Nepal", "Netherlands Antilles"=>"Netherlands Antilles", "Nicarágua"=>"Nicarágua", "Nigéria"=>"Nigéria", "Noruega"=>"Noruega", "Nova Zelândia"=>"Nova Zelândia", "Omã"=>"Omã", "Panamá"=>"Panamá", "Paquistão"=>"Paquistão", "Paraguai"=>"Paraguai", "Peru"=>"Peru", "Polinésia Francesa"=>"Polinésia Francesa", "Polônia"=>"Polônia", "Portugal"=>"Portugal", "Qatar"=>"Qatar", "Quênia"=>"Quênia", "República Dominicana"=>"República Dominicana", "Romênia"=>"Romênia", "Rússia"=>"Rússia", "Santa Helena"=>"Santa Helena", "Santa Kitts e Nevis"=>"Santa Kitts e Nevis", "Santa Lúcia"=>"Santa Lúcia", "São Vicente"=>"São Vicente", "Singapura"=>"Singapura", "Síria"=>"Síria", "Spiemich"=>"Spiemich", "Sudão"=>"Sudão", "Suécia"=>"Suécia", "Suiça"=>"Suiça", "Suriname"=>"Suriname", "Swaziland"=>"Swaziland", "Tailândia"=>"Tailândia", "Taiwan"=>"Taiwan", "Tchecoslováquia"=>"Tchecoslováquia", "Tonga"=>"Tonga", "Trinidad e Tobago"=>"Trinidad e Tobago", "Turksccai"=>"Turksccai", "Turquia"=>"Turquia", "Tuvalu"=>"Tuvalu", "Uruguai"=>"Uruguai", "Vanuatu"=>"Vanuatu", "Wallis e Fortuna"=>"Wallis e Fortuna", "West Bank"=>"West Bank", "Yémen"=>"Yémen", "Zaire"=>"Zaire", "Zimbabwe"=>"Zimbabwe"], $photo->country != null ? $photo->country : "Brasil") }}<br>
                        <div class="error">{{ $errors->first('country') }} </div>
                    </p>
                </div>
                </td>
              </tr>
              <tr><td>
                <div class="two columns alpha"><p>{{ Form::label('state', 'Estado:') }}</p></div>
                <div class="two columns omega">
                <p>{{ Form::select('state', [""=>"Escolha o Estado", "AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá", "BA"=>"Bahia", "CE"=>"Ceará", "DF"=>"Distrito Federal", "ES"=>"Espirito Santo", "GO"=>"Goiás", "MA"=>"Maranhão", "MG"=>"Minas Gerais", "MS"=>"Mato Grosso do Sul", "MT"=>"Mato Grosso", "PA"=>"Pará", "PB"=>"Paraíba", "PE"=>"Pernambuco", "PI"=>"Piauí", "PR"=>"Paraná", "RJ"=>"Rio de Janeiro", "RN"=>"Rio Grande do Norte", "RO"=>"Rondônia", "RR"=>"Roraima", "RS"=>"Rio Grande do Sul", "SC"=>"Santa Catarina", "SE"=>"Sergipe", "SP"=>"São Paulo", "TO"=>"Tocantins"], $photo->state) }} <br>
                  <div class="error">{{ $errors->first('state') }}</div>
                </p>
                </td>
              </tr>
              <tr><td>
                <div class="two columns alpha"><p>{{ Form::label('city', 'Cidade:') }}</p></div>
                <div class="two columns omega">
                  <p>{{ Form::text('city', $photo->city) }}<br>
                  </p>
                </div></td>
              </tr>

              <tr><td>
                <div class="two columns alpha"><p>{{ Form::label('street', 'Endereço:') }}</p></div>
                <div class="two columns omega">
                  <p>{{ Form::text('street', $photo->street) }} <br>
                  </p>
                </div>
                </td>
              </tr>

              <tr><td>
                  <div class="two columns alpha"><p>{{ Form::label('imageAuthor', 'Autor da imagem*:') }}</p></div>
                  <div class="two columns omega">
                    <p>
                      {{ Form::text('imageAuthor', $institution->name) }}
                       <br>
                      <div class="error">{{ $errors->first('imageAuthor') }}</div>
                    </p>
                  </div></td>
              </tr>

             <tr><td>
                <div class="two columns alpha"><p>{{ Form::label('imageDate', 'Data da imagem:') }}</p></div>
                <div class="five columns omega">
                    @if (($photo->dataCriacao)!= null && $photo->imageDateType == "date")
                      <p>{{ Form::text('image_date',date("d/m/Y",strtotime($photo->dataCriacao)),array('id' => 'datePickerImageDate','placeholder'=>'DD/MM/AAAA')) }}
                    @else
                      <p>{{ Form::text('image_date','',array('id' => 'datePickerImageDate','placeholder'=>'DD/MM/AAAA')) }}
                    @endif
                          <span class="space_txt_element">Não sabe a data precisa?
                            <a onclick="date_visibility('date_img_inaccurate');" >Clique aqui.</a>
                          </span>
                          <br> <div id="error_image_date" class="error">{{ $errors->first('image_date') }}</div>
                      </p>
                      <p>
                          <div id="date_img_inaccurate" style="display:none;">
                            @include('photos.includes.dateImage')
                          </div>
                          <label id="answer_date_image" class="resultDateWork"></label>
                      </p>
                </div></td>
            </tr>
              <tr><td>
                <div class="two columns alpha"><p>{{ Form::label('observation', 'Observações:') }}</p></div>
                <div class="two columns omega">
                  <p>
                    {{ Form::textarea('observation',$photo->observation) }} <br>
                  </p>
                </div>
              </td>
              </tr>

              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div>
           <div class="twelve columns omega row">
            <h4>Licença</h4></br>

               <div class="twelve columns omega row">
            <div class="form-row">
              <input type="radio" onclick="enableLicencenseChoice()" name="authorized" value="1" id="authorized" {{$photo->authorized == 1 ? "checked" : ""}}>
              <label for="authorized">Sou o autor da imagem ou possuo permissão expressa do autor para disponibilizá-la no Arquigrafia</label><br class="clear">
            </div>
            <div class="form-row">
              <input type="radio" onclick="disableLicenseChoice()" name="authorized" value="0" id="authorized" {{$photo->authorized == 0 ? "checked" : ""}}>
              <label for="authorized">Aguardando autorização do autor</label><br class="clear">
             </div>
          </div>
               <br>
               <br>
               <p>
               Escolho a licença <a href="http://creativecommons.org/licenses/?lang=pt_BR" id="creative_commons" target="_blank" style="text-decoration:underline; line-height:16px;">Creative Commons</a>, para publicar a imagem, com as seguintes permissões:
            </p>
          </div>
          <div class="four columns" id="creative_commons_left_form">
            Permitir o uso comercial da imagem?
            <br>
             <div class="form-row">
              <input type="radio" onclick="authorization()" name="allowCommercialUses" value="YES" id="allowCommercialUsesYES" {{$photo->allowCommercialUses == 'YES' ? "checked" : ""}}>
              <label for="allowCommercialUsesYES">Sim</label><br class="clear">
             </div>
             <div class="form-row">
              <input type="radio" onclick="authorization()" name="allowCommercialUses" value="NO" id="allowCommercialUsesNO" {{$photo->allowCommercialUses == 'NO' ? "checked" : ""}}>
              <label for="allowCommercialUsesNO">Não</label><br class="clear">
             </div>
          </div>
          <div class="four columns" id="creative_commons_right_form">
            Permitir modificações em sua imagem?
            <br>
            <div class="form-row">
              <input type="radio" onclick="authorization()" name="allowModifications" value="YES" id="allowModificationsYES" {{$photo->allowModifications == 'YES' ? "checked" : ""}}>
              <label for="allowModificationsYES">Sim</label><br class="clear">
            </div>
            <div class="form-row">
              <input type="radio" onclick="authorization()" name="allowModifications" value="YES_SA" id="allowModificationsYES_SA" {{$photo->allowModifications == 'YES_SA' ? "checked" : ""}}>
              <label for="allowModificationsYES_SA">Sim, contanto que os outros compartilhem de forma semelhante</label><br class="clear">
             </div>
            <div class="form-row">
              <input type="radio" onclick="authorization()" name="allowModifications" value="NO" id="allowModificationsNO" {{$photo->allowModifications == 'NO' ? "checked" : ""}}>
              <label for="allowModificationsNO">Não</label><br class="clear">
            </div>
          </div>
          <div class="twelve columns">
            <input name="enviar" type="submit" class="btn" value="ENVIAR">
            <a href="{{ URL::to('/photos/' . $photo->id) }}" class='btn'>VOLTAR</a>&nbsp;&nbsp;
            <!--<input type="button" id="btnOpenDialogRepopulate" value="ENVIAR" class="btn">-->
            <div id="dialog-confirm" title=" "></div>
          </div>
      </div>
      {{ Form::close() }}

  </div>

  </div>
  <script type="text/javascript">
  $(document).ready(function() {

      var typeSaved  = "{{ $type }}";
      var typeChecked  = "{{Input::old('type')}}";

      if(typeChecked == null || typeChecked == ""){
          if(typeSaved == "photo" ){
              document.getElementById('type_photo').checked = true;
              document.getElementById('video').value = "";
              $('#divVideo').hide();
              $('#divPhoto').show();
          }else{
              var elem = document.getElementById('old_image');
              elem.parentNode.removeChild(elem);
              document.getElementById('type_video').checked = true;
                  $('#divVideo').show();
                  $('#divPhoto').hide();
          }
      }else{
        //checkado

        if(typeChecked == "video" ){
                if(typeSaved == "video" ){
                    var elem = document.getElementById('old_image');
                    elem.parentNode.removeChild(elem);
                }
                document.getElementById('type_video').checked = true;
                $('#divVideo').show();
                $('#divPhoto').hide();
        }else{
            document.getElementById('type_photo').checked = true;
            document.getElementById('video').value = null;
            $('#divVideo').hide();
            $('#divPhoto').show();

        }
      }


      $('input[type=radio][name=type]').change(function(){
        if(this.value == "video"){
          $('#divVideo').show();
          $('#divPhoto').hide();
        } if(this.value == "photo") {
          $('#divVideo').hide();
          $('#divPhoto').show();
        }
    });


    /* Methods to be called when all html document be ready */
    showTags({{json_encode($tagsArea)}},$('#tagsArea'),$('#tags_input'));

    //authors
    $('#work_authors').textext({ plugins: 'tags' });
      //alert('{{Input::old('work_authors')}}');
        @if(Input::old('work_authors') != null)
            //explode
            <?php $work_authors = explode ('","', Input::old('work_authors')); ?>
        @endif
        var string_author = "";
        @if (isset($work_authors) && $work_authors != null)
            @foreach ( $work_authors as $work_author )
                string_author = '{{$work_author}}';
                string_author = string_author.replace('["',"");
                string_author = string_author.replace('"]',"");

                $('#work_authors').textext()[0].tags().addTags([ string_author ]);
            @endforeach
        @endif

        $('#add_work_authors').click(function(e) {
            e.preventDefault();
            authorsList();
        });
        $('#photo_workAuthor').keypress(function(e) {
            var key = e.which || e.keyCode;
            if(key ==13)
               authorsList();
            if (key == 44 || key == 46 || key == 59) // key = , ou Key = . ou key = ;
                e.preventDefault();
        });

        function authorsList(){
            var authors = $('#photo_workAuthor').val();
            if (authors == '') return;
            $('#work_authors').textext()[0].tags().addTags([ authors ]);
            $('#photo_workAuthor').val('');
        }



    @if($dateYear != NULL)
      var dateYear = "{{$dateYear}}";
      retrieveYearDate(dateYear);
    @endif


    @if($centuryInput != null || $centuryInput != "" )    //
      var centuryInput = "{{$centuryInput}}";//"{{Input::old('century')}}";
      showPeriodCentury(centuryInput);
      retrieveCentury(centuryInput);
      //get filter
      //filterDecadesOfCentury(centuryInput);
      //alert(centuryInput);
    @endif

    @if($decadeInput != null || $decadeInput!="" )
        var decadeInput = "{{$decadeInput}}";
      retrieveDecade(decadeInput);
      getCenturyOfDecade(decadeInput,"workDate");
    @endif

     @if($centuryImageInput != null || $centuryImageInput != "" )    //
      var centuryImageInput = "{{$centuryImageInput}}";//"{{Input::old('century')}}";
      showPeriodCenturyImage(centuryImageInput);
      retrieveCenturyImage(centuryImageInput);
      //get filter
      //filterDecadesOfCentury(centuryInput);
      //alert(centuryImageInput);
    @endif

    @if($decadeImageInput != null || $decadeImageInput!="" )
        var decadeImageInput = "{{$decadeImageInput}}";
        retrieveDecadeImage(decadeImageInput);
        getCenturyOfDecade(decadeImageInput,"imageDate");
        //alert("fueraForm"+decadeImageInput);
    @endif
    //window.onload = resultSelectDateWork;

     if("{{ $centuryInput }}" != "" || "{{ $decadeInput}} " != "" ){
                window.onload = resultSelectDateWork("otherDate");
               // alert("other Decade-load");
        }

     if("{{Input::old('century')}}" != "" || "{{Input::old('decade_select')}}" != "" ){
                window.onload = resultSelectDateWork("otherDate");
               // alert("other Decade-old");
        }

     if("{{ $centuryImageInput }}" != "" || "{{ $decadeImageInput }}" != "" ){
                window.onload = resultSelectDateWork("date_img_inaccurate");
               // alert("iamge carga");
      }

     if("{{Input::old('century_image')}}" != "" || "{{Input::old('decade_select_image')}}" != "" ){
                window.onload = resultSelectDateWork("date_img_inaccurate");
              //  alert("image por error o recuperac");
      }

   });
  function enableLicencenseChoice() {
    var allowModifications = document.getElementsByName('allowModifications');
    var allowCommercialUses = document.getElementsByName('allowCommercialUses');
    var i;
    for (i = 0; i < allowModifications.length; i++) {
      allowModifications[i].disabled = false;
    }
    for (i = 0; i < allowCommercialUses.length; i++) {
      allowCommercialUses[i].disabled = false;
    }
  }
  function disableLicenseChoice() {
    var allowModifications = document.getElementsByName('allowModifications');
    var allowCommercialUses = document.getElementsByName('allowCommercialUses');
    var i;
    allowModifications[allowModifications.length-1].checked = true;
    allowCommercialUses[allowCommercialUses.length-1].checked = true;
  }
  function authorization() {
    var authorized = document.getElementsByName('authorized');
    var allowModifications = document.getElementsByName('allowModifications');
    var allowCommercialUses = document.getElementsByName('allowCommercialUses');
    if (allowModifications[allowModifications.length-1].checked == false || allowCommercialUses[allowCommercialUses.length-1].checked == false) {
      authorized[0].checked = true;
    }
  }
  </script>
@stop
