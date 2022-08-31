@extends('layouts.default')
@section('head')
<title>Arquigrafia - Fotos - Upload</title>

<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/styletags.css" />

<!-- TEXTEXT -->
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.core.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.plugin.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="{{ URL::to("/") }}/css/textext.plugin.tags.css" />
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.js"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.core.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.tags.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.autocomplete.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.suggestions.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.filter.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/textext.plugin.ajax.js" charset="utf-8"></script>

<! -- GENERAL JS -->
<script type="text/javascript" src="{{ URL::to("/") }}/js/tags-autocomplete.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/repopulateForm.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/tag-list.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/tag-autocomplete-part.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/city-autocomplete.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/date-work.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/rotate.js" charset="utf-8"></script>
<script type="text/javascript" src="{{ URL::to("/") }}/js/readURL.js" charset="utf-8"></script>

<!-- JQUERY -->
<link rel="stylesheet" href="{{ URL::to("/") }}/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{{ URL::to("/") }}/js/jquery-ui/jquery-ui.min.js" charset="utf-8"></script>
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
	/* Style select*/
  fieldset {
    border: 0;
    margin: 0 0 0px -10px;
    font-size: 10px;
  }
  label {
    display: block;
    margin: 30px 0 0 0;
  }
  select {
    width: 160px;
  }
  .overflow {
    height: 350px;
  }
</style>
@stop
@section('content')
	<script type="text/javascript">
		$( window ).on('load',function() {
      // Tags
      $('#tagsArea').textext({ plugins: 'tags' });
      @if(Input::old('tags')!=null)
        <?php $tags = explode (",", Input::old('tags')); ?>
      @endif

      @if (isset($tags) && $tags !=null)
        @foreach ( $tags as $tag )
            $('#tagsArea').textext()[0].tags().addTags([ {{ '"' . $tag . '"' }} ]);
        @endforeach
      @endif

			$("#preview_photo").hide();
			if (document.getElementById("new_album-name").value != "") {
				var select_album = document.getElementsByClassName('select-album');
				var new_album = document.getElementsByClassName('new-album-name');
				var selector = document.getElementById('photo_album');
				var i;
				for (i = 0; i < select_album.length; i++) {
	 					select_album[i].style.display = "block";
				}
				for (i = 0; i < new_album.length; i++) {
						new_album[i].style.display = "none";
				}
				for (i = 0; i < selector.options.length; i++) {
					if (selector.options[i].text == document.getElementById("new_album-name").value) {
						selector.selectedIndex = i;
					}
				}
				document.getElementById("new_album-name").value = "";
			} else if (document.getElementById("photo_album").value != "") {
				var select_album = document.getElementsByClassName('select-album');
				var new_album = document.getElementsByClassName('new-album-name');
				var i;
				for (i = 0; i < select_album.length; i++) {
						select_album[i].style.display = "block";
				}
				for (i = 0; i < new_album.length; i++) {
						new_album[i].style.display = "none";
				}
			}
		});
	</script>
	<div class="container">
		<div>
			{{ Form::open(array('url' => "institutions/save", 'files'=> true , 'id'=>"formInstitutional")) }}
				<div class="twelve columns row step-1">
					<a href="{{ URL::to('/drafts') }}" class="right">Uploads incompletos</a>
					<h1><span class="step-text">Upload</span></h1>
					<div class="eleven columns alpha" id="media_type">
				        <br>
				        <div class="form-row">
				            <input type="radio" name="type" value="photo" id="type_photo" checked="checked">
				            <label for="type_photo">Foto</label><br class="clear">
				        </div>
				        <div class="form-row">
				            <input type="radio" name="type" value="video" id="type_video">
				            <label for="type_video">Vídeo</label><br class="clear">
				        </div>
				    </div>
					<div id="divPhoto">
						<div class="four columns alpha">
							<img src="" id="preview_photo">
                <div id="image_rotate" style="display:none;">
                <br />
                <a class="btn right" onclick="Rotate(document.getElementById('preview_photo'), Math.PI/2);">
                  Girar 90° para direita
                </a>
                <a class="btn left" onclick="Rotate(document.getElementById('preview_photo'), -Math.PI/2);">
                  Girar 90° para esquerda
                </a>
                </div>
                <br />
							<p>
								{{ Form::label('photo','Imagem:') }}
								{{ Form::file('photo', array('id'=>'imageUpload', 'onchange' => 'readURL(this);')) }}
								<div class="error">{{ $errors->first('photo') }}</div>
                <br />
							</p>
							<br>
						</div>
					</div>
					<div id="divVideo" class="twelve columns alpha">
						</br>
				        <div class="two columns alpha">{{ Form::label('video', 'Link do vídeo youtube ou vimeo:') }}</div>
				        <div class="four columns alpha">
				            <p>
                      {{ Form::text('video', $video, array('id' => 'video','style'=>'width:280px')) }} <br />
				            </p>
				            <div class="error">{{ $errors->first('video') }}</div>
				            <p>
                      Ex. https://www.youtube.com/watch?v=XXXXXXXX  <br />
				              ou  https://vimeo.com/XXXXXXXX
                    </p>
				        </div>
					</div>
				</div>
				<div id="registration" class="twelve columns row step-2">
					<h1><span class="step-text">Informações da imagem</span></h1>
					<p>(*) Campos obrigatórios.</p>
					<p>{{ Form::hidden('pageSource', $pageSource) }} </p>
					@if ( Input::old('draft_id', false) )
						{{ Form::hidden('draft_id', Input::old('draft_id')) }}
					@endif
					<br>
					<div class="eight columns alpha row">
						<table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">
							@if(Session::get('institutionId'))
							<tr>
								<td>
									<div class="two columns alpha">
										<p>{{ Form::label('support', 'Suporte*:') }}</p>
									</div>
									<div class="three columns omega">
										<p>{{ Form::text('support') }} <br>
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
										<p>{{ Form::text('tombo') }} <br>
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
										<p>{{ Form::text('subject') }} <br>
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
											{{ Form::text('hygieneDate','',array('id' => 'datePickerHygieneDate','placeholder'=>'DD/MM/AAAA')) }}

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
											{{ Form::text('backupDate','',array('id' => 'datePickerBackupDate','placeholder'=>'DD/MM/AAAA')) }}
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
										<p>{{ Form::text('characterization') }} <br>
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
							@endif
							<tr>
								<td>
									<div class="two columns alpha">
										<p>{{ Form::label('name', 'Título*:') }}</p>
									</div>
									<div class="three columns omega">
										<p>{{ Form::text('photo_name') }} <br>
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
											{{ Form::textarea('description') }}<br>
										</p>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="two columns alpha"><p>{{ Form::label('tags_input', 'Tags*:') }}</p></div>
                  <div class="four columns">
                    <p>
                      {{ Form::text('tags_input') }}
                      <button class="btn" id="add_tag" style="font-size: 11px;">ADICIONAR TAG</button>
                      <br>
                      <div class="error">{{ $errors->first('tags') }}</div>
                    </p>
                  </div>
									<div class="five columns alpha">
                    <textarea name="tagsArea" id="tagsArea" cols="60" rows="1" style="display: none;"></textarea>
									</div>
								</td>
							</tr>
							<tr><td></td></tr>
							<tr><td> @include('photos.includes.workAuthorInst') </td></tr>
							<tr><td></td></tr>
							<tr><td>
		 						<div class="two columns alpha">
		 							<p>{{ Form::label('workDate', 'Ano de conclusão da obra:') }}</p>
		 						</div>
		 						<div class="six columns omega">
											<p>
												@include('photos.includes.dateList')
				 								<span class="space_txt_element">Não sabe a data precisa?
				 									<a onclick="date_visibility('otherDate');" >Clique aqui.</a>
				 								</span>
				 							</p>
				 							<p>
				 								<div id="otherDate" class="div_institutional" style="display:none;">
				 									@include('photos.includes.dateWork')
				 									<!--<a onclick="close_other_date('otherDate');" class="btn right" >OK</a>-->
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
									<p>

										{{ Form::select('country', [ "Afeganistão"=>"Afeganistão", "África do Sul"=>"África do Sul", "Albânia"=>"Albânia", "Alemanha"=>"Alemanha", "América Samoa"=>"América Samoa", "Andorra"=>"Andorra", "Angola"=>"Angola", "Anguilla"=>"Anguilla", "Antartida"=>"Antartida", "Antigua"=>"Antigua", "Antigua e Barbuda"=>"Antigua e Barbuda", "Arábia Saudita"=>"Arábia Saudita", "Argentina"=>"Argentina", "Aruba"=>"Aruba", "Australia"=>"Australia", "Austria"=>"Austria", "Bahamas"=>"Bahamas", "Bahrain"=>"Bahrain", "Barbados"=>"Barbados", "Bélgica"=>"Bélgica", "Belize"=>"Belize", "Bermuda"=>"Bermuda", "Bhutan"=>"Bhutan", "Bolívia"=>"Bolívia", "Botswana"=>"Botswana", "Brasil"=>"Brasil", "Brunei"=>"Brunei", "Bulgária"=>"Bulgária", "Burundi"=>"Burundi", "Cabo Verde"=>"Cabo Verde", "Camboja"=>"Camboja", "Canadá"=>"Canadá", "Chade"=>"Chade", "Chile"=>"Chile", "China"=>"China", "Cingapura"=>"Cingapura", "Colômbia"=>"Colômbia", "Djibouti"=>"Djibouti", "Dominicana"=>"Dominicana", "Emirados Árabes"=>"Emirados Árabes", "Equador"=>"Equador", "Espanha"=>"Espanha", "Estados Unidos"=>"Estados Unidos", "Fiji"=>"Fiji", "Filipinas"=>"Filipinas", "Finlândia"=>"Finlândia", "França"=>"França", "Gabão"=>"Gabão", "Gaza Strip"=>"Gaza Strip", "Ghana"=>"Ghana", "Gibraltar"=>"Gibraltar", "Granada"=>"Granada", "Grécia"=>"Grécia", "Guadalupe"=>"Guadalupe", "Guam"=>"Guam", "Guatemala"=>"Guatemala", "Guernsey"=>"Guernsey", "Guiana"=>"Guiana", "Guiana Francesa"=>"Guiana Francesa", "Haiti"=>"Haiti", "Holanda"=>"Holanda", "Honduras"=>"Honduras", "Hong Kong"=>"Hong Kong", "Hungria"=>"Hungria", "Ilha Cocos (Keeling)"=>"Ilha Cocos (Keeling)", "Ilha Cook"=>"Ilha Cook", "Ilha Marshall"=>"Ilha Marshall", "Ilha Norfolk"=>"Ilha Norfolk", "Ilhas Turcas e Caicos"=>"Ilhas Turcas e Caicos", "Ilhas Virgens"=>"Ilhas Virgens", "Índia"=>"Índia", "Indonésia"=>"Indonésia", "Inglaterra"=>"Inglaterra", "Irã"=>"Irã", "Iraque"=>"Iraque", "Irlanda"=>"Irlanda", "Irlanda do Norte"=>"Irlanda do Norte", "Islândia"=>"Islândia", "Israel"=>"Israel", "Itália"=>"Itália", "Iugoslávia"=>"Iugoslávia", "Jamaica"=>"Jamaica", "Japão"=>"Japão", "Jersey"=>"Jersey", "Kirgizstão"=>"Kirgizstão", "Kiribati"=>"Kiribati", "Kittsnev"=>"Kittsnev", "Kuwait"=>"Kuwait", "Laos"=>"Laos", "Lesotho"=>"Lesotho", "Líbano"=>"Líbano", "Líbia"=>"Líbia", "Liechtenstein"=>"Liechtenstein", "Luxemburgo"=>"Luxemburgo", "Maldivas"=>"Maldivas", "Malta"=>"Malta", "Marrocos"=>"Marrocos", "Mauritânia"=>"Mauritânia", "Mauritius"=>"Mauritius", "México"=>"México", "Moçambique"=>"Moçambique", "Mônaco"=>"Mônaco", "Mongólia"=>"Mongólia", "Namíbia"=>"Namíbia", "Nepal"=>"Nepal", "Netherlands Antilles"=>"Netherlands Antilles", "Nicarágua"=>"Nicarágua", "Nigéria"=>"Nigéria", "Noruega"=>"Noruega", "Nova Zelândia"=>"Nova Zelândia", "Omã"=>"Omã", "Panamá"=>"Panamá", "Paquistão"=>"Paquistão", "Paraguai"=>"Paraguai", "Peru"=>"Peru", "Polinésia Francesa"=>"Polinésia Francesa", "Polônia"=>"Polônia", "Portugal"=>"Portugal", "Qatar"=>"Qatar", "Quênia"=>"Quênia", "República Dominicana"=>"República Dominicana", "Romênia"=>"Romênia", "Rússia"=>"Rússia", "Santa Helena"=>"Santa Helena", "Santa Kitts e Nevis"=>"Santa Kitts e Nevis", "Santa Lúcia"=>"Santa Lúcia", "São Vicente"=>"São Vicente", "Singapura"=>"Singapura", "Síria"=>"Síria", "Spiemich"=>"Spiemich", "Sudão"=>"Sudão", "Suécia"=>"Suécia", "Suiça"=>"Suiça", "Suriname"=>"Suriname", "Swaziland"=>"Swaziland", "Tailândia"=>"Tailândia", "Taiwan"=>"Taiwan", "Tchecoslováquia"=>"Tchecoslováquia", "Tonga"=>"Tonga", "Trinidad e Tobago"=>"Trinidad e Tobago", "Turksccai"=>"Turksccai", "Turquia"=>"Turquia", "Tuvalu"=>"Tuvalu", "Uruguai"=>"Uruguai", "Vanuatu"=>"Vanuatu", "Wallis e Fortuna"=>"Wallis e Fortuna", "West Bank"=>"West Bank", "Yémen"=>"Yémen", "Zaire"=>"Zaire", "Zimbabwe"=>"Zimbabwe"], "Brasil") }}<br>


										<div class="error">{{ $errors->first('country') }}</div>
									</p>
								</div>
							</td>
							</tr>
							<tr><td>
								<div class="two columns alpha"><p>{{ Form::label('state', 'Estado:') }}</p></div>
								<div class="two columns omega">
									{{ Form::select('state', [""=>"Escolha o Estado", "AC"=>"Acre", "AL"=>"Alagoas", "AM"=>"Amazonas", "AP"=>"Amapá", "BA"=>"Bahia", "CE"=>"Ceará", "DF"=>"Distrito Federal", "ES"=>"Espirito Santo", "GO"=>"Goiás", "MA"=>"Maranhão", "MG"=>"Minas Gerais", "MS"=>"Mato Grosso do Sul", "MT"=>"Mato Grosso", "PA"=>"Pará", "PB"=>"Paraíba", "PE"=>"Pernambuco", "PI"=>"Piauí", "PR"=>"Paraná", "RJ"=>"Rio de Janeiro", "RN"=>"Rio Grande do Norte", "RO"=>"Rondônia", "RR"=>"Roraima", "RS"=>"Rio Grande do Sul", "SC"=>"Santa Catarina", "SE"=>"Sergipe", "SP"=>"São Paulo", "TO"=>"Tocantins"], "") }} <br>

									<div class="error">{{ $errors->first('state') }}</div>
								</td>
							</tr>
							<tr><td>
								<div class="two columns alpha"><p>{{ Form::label('city', 'Cidade:') }}</p></div>
								<div class="two columns omega">
									<p>
										{{ Form::text('city') }} <br>
										<div class="error">{{ $errors->first('city') }}</div>
									</p>
								</div>
								</td>
							</tr>

							<tr><td>
								<div class="two columns alpha"><p>{{ Form::label('street', 'Endereço:') }}</p></div>
								<div class="two columns omega">
									<p>
										{{ Form::text('street') }} <br>
									</p>
								</div>
								<td>
							</tr>
							<tr><td>
								<div class="two columns alpha"><p>Adicione a um álbum:</p></div>
								<div class="three columns omega" style="white-space : nowrap;">
									<p>
										<div class="btn" onclick="newAlbumInput()" style="font-size: 11px; width: 76px; display: inline-block">NOVO ÁLBUM</div>
										<div class="btn" onclick="selectAlbumInput()" style="font-size: 11px; width: 104px; display: inline-block">ESCOLHER ÁLBUM</div>
									</p>
								</div>
								</td>
							</tr>
							<tr><td>
								<div class="two columns alpha select-album" style="display: none">
									<p>{{ Form::label('photo_album', 'Adicionar ao álbum:') }}</p>
								</div>
								<div class="two columns omega">
									<p class="select-album" style="display: none;">
										{{ Form::select('photo_album', $albums, "") }} <br>
									</p>
								</div>
								</td>
							</tr>
							<tr><td>
								<div class="two columns alpha new-album-name" style="display: none"><p>{{ Form::label('new_album-name', 'Digite o nome do novo álbum:') }}</p></div>
								<div class="two columns omega">
									<p class="new-album-name" style="display: none;">
										{{ Form::text('new_album-name') }} <br>
									</p>
								</div>
								</td>
							</tr>
							<tr><td>

									<div class="two columns alpha"><p>{{ Form::label('imageAuthor', 'Autor(es) da imagem*:') }}</p></div>
									<div class="five columns omega">
										<p>
											{{ Form::text('imageAuthor', $institution->name, array('id' => 'imageAuthor','style'=>'width:280px')) }}
											 <br>
											<div class="error">{{ $errors->first('imageAuthor') }}</div>
										</p>
										<p>Separe os autores diferentes com ";"</p>
									</div>
								</td>
							</tr>
							<tr> <td>
				 						<div class="two columns alpha"><p>{{ Form::label('imageDate', 'Data da imagem:') }}</p></div>
				 						<div class="fivemidUpdateInst columns omega">
												 	<p>{{ Form::text('image_date','',array('id' => 'datePickerImageDate','placeholder'=>'DD/MM/AAAA')) }}
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
										</div>
										</td>
									</tr>
							<tr><td>
								<div class="two columns alpha"><p>{{ Form::label('observation', 'Observações:') }}</p></div>
								<div class="two columns omega">
									<p>
										{{ Form::textarea('observation') }} <br>

									</p>
								</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						<script type="text/javascript">
							function newAlbumInput() {
								var select_album = document.getElementsByClassName('select-album');
								var new_album = document.getElementsByClassName('new-album-name');
								var i;
								for (i = 0; i < select_album.length; i++) {
										select_album[i].style.display = "none";
								}
								for (i = 0; i < new_album.length; i++) {
										new_album[i].style.display = "block";
								}
								document.getElementById("photo_album").value = "";
							}

							function selectAlbumInput() {
								var select_album = document.getElementsByClassName('select-album');
								var new_album = document.getElementsByClassName('new-album-name');
								var i;
								for (i = 0; i < select_album.length; i++) {
										select_album[i].style.display = "block";
								}
								for (i = 0; i < new_album.length; i++) {
										new_album[i].style.display = "none";
								}
								document.getElementById("new_album-name").value = "";
							}
						</script>
					</div>
					<div class="five columns omega row">
						<table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="0">


						</table>
					</div>
					<div class="twelve columns omega row">
						<div class="form-row">
							{{ Form::radio('authorized', '1', true,
								['id' => 'authorized', 'onclick' => 'enableLicencenseChoice()']) }}
							<label for="authorized">Sou o autor da imagem ou possuo permissão expressa do autor para disponibilizá-la no Arquigrafia</label><br class="clear">
						</div>
						<div class="form-row">
							{{ Form::radio('authorized', '0', false,
								['id' => 'unauthorized', 'onclick' => 'disableLicencenseChoice()']) }}
							<label for="unauthorized">Aguardando autorização do autor</label><br class="clear">
						 </div>
					</div>
					<div class="twelve columns omega row">
						<label for="terms" generated="true" class="error" style="display: inline-block; "></label>
						Escolho a licença <a href="http://creativecommons.org/licenses/?lang=pt_BR" id="creative_commons" target="_blank" style="text-decoration:underline; line-height:16px;">Creative Commons</a>, para publicar a imagem, com as seguintes permissões:
					</div>

					<div class="four columns" id="creative_commons_left_form">
						Permitir o uso comercial da imagem?
						<br>
						 <div class="form-row">
							<input type="radio" onclick="authorization()" name="allowCommercialUses" value="YES" id="allowCommercialUsesYES" checked="checked">
							<label for="allowCommercialUsesYES">Sim</label><br class="clear">
						 </div>
						 <div class="form-row">
							<input type="radio" onclick="authorization()" name="allowCommercialUses" value="NO" id="allowCommercialUsesNO">
							<label for="allowCommercialUsesNO">Não</label><br class="clear">
						 </div>
					</div>
					<div class="four columns" id="creative_commons_right_form">
						Permitir modificações em sua imagem?
						<br>
						<div class="form-row">
							<input type="radio" onclick="authorization()" name="allowModifications" value="YES" id="allowModificationsYES" checked="checked">
							<label for="allowModificationsYES">Sim</label><br class="clear">
						</div>
						<div class="form-row">
							<input type="radio" onclick="authorization()" name="allowModifications" value="YES_SA" id="allowModificationsYES_SA">
							<label for="allowModificationsYES_SA">Sim, contanto que os outros compartilhem de forma semelhante</label><br class="clear">
						</div>
						<div class="form-row">
							<input type="radio" onclick="authorization()" name="allowModifications" value="NO" id="allowModificationsNO">
							<label for="allowModificationsNO">Não</label><br class="clear">
						</div>
					</div>
					<div class="twelve columns">
						<div><input name="enviar" type="submit" class="btn" value="ENVIAR"></div>
						<div id="divDraftButton"><input name="draft" type="submit" class="btn" value="SALVAR INFORMAÇÕES SEM A IMAGEM"></div>
						<div id="dialog-confirm" title=" "></div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>

<script type="text/javascript">
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
<script type="text/javascript">
	$(document).ready(function() {
		var typeChecked  = "{{Input::old('type')}}";
    if(typeChecked == "video" ) {
      document.getElementById('type_video').checked = true;
      $('#divVideo').show();
      $('#divPhoto').hide();
      $('#divDraftButton').hide();
    } else {
      document.getElementById('type_photo').checked = true;
      document.getElementById('video').value = null;
      $('#divVideo').hide();
      $('#divPhoto').show();
      $('#divDraftButton').show();
    }

    $('input[type=radio][name=type]').change(function(){
      if(this.value == "video"){
        $('#divVideo').show();
        $('#divPhoto').hide();
        $('#divDraftButton').hide();
      } if(this.value == "photo") {
        $('#divVideo').hide();
        $('#divPhoto').show();
        $('#divDraftButton').show();
      }
    });

		if ({{ Input::old('autoOpenModal','false') }}) {
			var text;
			var url;
			if ('{{ Input::old("draft", null) }}'.length) {
				text = "<b>Informações sem a imagem salvas com sucesso! Para completar os dados acesse a área de perfil do acervo ou o link 'Uploads incompletos' nesta página</b><br><br>Gostaria de utilizar os dados anteriores novamente?";
				url = "{{ URL::to('/institutions/' . $institution->id) }}";
			} else {
				text = "<b>Cadastro de imagem realizado com sucesso!</b><br><br>Gostaria de utilizar os dados da imagem cadastrada para o próximo upload?";
				url = "{{ URL::to('/photos/' . Input::old('photoId')) }}"
			}
			$( "#dialog-confirm" ).html(text);
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:180,
				modal: true,
				buttons: {
					"Sim": function() {
						$( this ).dialog( "close" );
					},
					"Não": function() {
						window.location.replace(url);
					}
				}
			});
		}
		var radio_checked_commercial = "{{ 'allowCommercialUses'. Input::old('allowCommercialUses') }}";
		var radio_checked_modifications = "{{ 'allowModifications' . Input::old('allowModifications') }}";
		if ((radio_checked_commercial != "allowCommercialUses") && (radio_checked_modifications != "allowModifications")) {
			if (radio_checked_commercial != "allowCommercialUsesYES") {
				document.getElementById("allowCommercialUsesYES").checked = false;
				document.getElementById(radio_checked_commercial).checked = true;
			}
			if (radio_checked_modifications != "allowModificationsYES") {
				document.getElementById("allowModificationsYES").checked = false;
				document.getElementById(radio_checked_modifications).checked = true;
			}
		}

		//authors
		$('#work_authors').textext({ plugins: 'tags' });
		@if(Input::old('work_authors')!= null)
			<?php $work_authors = explode (";", Input::old('work_authors')); ?>
		@endif

		@if (isset($work_authors) && $work_authors != null)
			@foreach ( $work_authors as $work_author )
				$('#work_authors').textext()[0].tags().addTags([ {{ '"' . $work_author . '"' }} ]);
			@endforeach
		@endif

		$('#add_work_authors').click(function(e) {
			e.preventDefault();
			authorsList();
		});

		$('#photo_workAuthor').keypress(function(e) {
			var key = e.which || e.keyCode;
			if(key ==13) authorsList();
			if (key == 44 || key == 46 || key == 59) // key = , ou Key = . ou key = ;
				e.preventDefault();
		});

		function authorsList(){
			var authors = $('#photo_workAuthor').val();
			if (authors == '') return;
			$('#work_authors').textext()[0].tags().addTags([ authors ]);
			$('#photo_workAuthor').val('');
		}

		@if( Input::old('century'))
			var centuryInput = "{{Input::old('century')}}";
			showPeriodCentury(centuryInput);
			retrieveCentury(centuryInput);
		@endif

		@if( Input::old('decade_select'))
			var decadeInput = "{{Input::old('decade_select')}}";
			retrieveDecade(decadeInput);
			getCenturyOfDecade(decadeInput,"workDate");
		@endif

		@if( Input::old('century_image'))
			var centuryInputImage = "{{Input::old('century_image')}}";
			showPeriodCenturyImage(centuryInputImage);
			retrieveCenturyImage(centuryInputImage);
		@endif

		@if( Input::old('decade_select_image'))
			var decadeInputImage = "{{ Input::old('decade_select_image') }}";
			retrieveDecadeImage(decadeInputImage);
			getCenturyOfDecade(decadeInputImage, "imageDate");
		@endif
		@if ($dates == false)
		 	window.onload = cleanToLoad;
		@endif

		if ({{ Input::old('dates','true') }}) {
			if ("{{ Input::old('century') }}" != "" || "{{ Input::old('decade_select') }}" != "") {
				window.onload = resultSelectDateWork("otherDate");
			}
		}

		if ({{ Input::old('dateImage','true') }}) {
			if ("{{ Input::old('century_image') }}" != "" || "{{ Input::old('decade_select_image') }}" != "") {
				window.onload = resultSelectDateWork("date_img_inaccurate");
			}
		}
	});
</script>
@stop
