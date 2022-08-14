@extends('layouts.default')

@section('head')

<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

@stop

@section('content')
    
    <!--   MEIO DO SITE - ÁREA DE NAVEGAÇÃO   -->
    <div id="content" class="container">
        
      <div class="eight columns justify">
        <!--   CONTEÚDO -->
        <h1>Chancela do Ministério da Cultura</h1>       
        <img class="left" src="img/project_photos/chancela.jpg" alt="Chancela" title="Chancela" />       
      </div>
      
      <!--   COLUNA DIREITA   
      <div class="four columns">
        <h2>Equipe</h2>
        
        <h3>Coordenador</h3>
        <ul>
          <li><small><a href="http://lattes.cnpq.br/9297674836039953" target="_blank">Prof. Dr. Artur Simões Rozestraten - FAUUSP</a></small></li>
        </ul>
        <br>
        
        <h3>Pesquisadores colaboradores</h3>
        <ul>
          <li><small><a href="http://lattes.cnpq.br/4507073071352893" target="_blank">Prof. Dr. Marco Aurélio Gerosa - IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/5382764179565796" target="_blank">Profa. Dra. Maria Laura Martinez - ECAUSP </a></small></li>
          <li><small><a href="http://lattes.cnpq.br/2342739419247924" target="_blank">Prof. Dr. Fabio Kon - IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/4574831233204082" target="_blank">Prof. Dr. Julio Roberto Katinsky - FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/3841951853755817" target="_blank">Prof. Dr. Luiz Américo de Souza Munari - FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/5218253515771817" target="_blank">Prof. Dr. Abílio Guerra e equipe VITRUVIUS</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/6695382764766281" target="_blank">Profa. Dra. Roberta Lima Gomes - Informática UFES</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/7471111924336519" target="_blank">Prof. Dr. Magnos Martinello - Informática UFES</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/3498148304153540" target="_blank">Prof. Dr. Juliano Maranhão - FDUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/4531433505351729" target="_blank">Profa. Dra. Vania Mara Alves Lima - ECAUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/8548608291351316" target="_blank">Profa. Dra. Renata Wassermann - IMEUSP</a></small></li>
          <li><small><a href="http://www.usp.br/fau/fau/secoes/biblio/index.html" target="_blank">Dina Uliana - Diretora biblioteca FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/4131992141506749" target="_blank">Eliana de Azevedo Marques - Biblioteca FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/7542993440465891" target="_blank">Elizabete da Cruz Neves - Biblioteca FAUUSP</a></small></li>
          <li><small><a href="http://www.usp.br/fau/fau/secoes/biblio/index.html" target="_blank">Rejane Alves - Biblioteca FAUUSP</a></small></li>
          <li><small><a href="http://www.cristianomascaro.com.br/" target="_blank">Prof. Dr. Cristiano Mascaro - Consultor de fotografia</a></small></li>
          <li><small><a href="http://www2.nelsonkon.com.br/" target="_blank">Arq. Nelson Kon - Consultor de fotografia</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/9781300883115022" target="_blank">Arq. Rodrigo Luiz Minot Gutierrez - SENAC / UNIUBE</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/6686522394728149" target="_blank">Arq. Leandro Lopes - ECAUSP</a></small></li>
        </ul>
        <br>
        
        <h3>Alunos participantes</h3>
        <ul>
          <li><small><a href="http://lattes.cnpq.br/0999376629124379" target="_blank">Ana Paula Oliveira Bertholdo - Doutoranda IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/3098839514190572" target="_blank">Aurelio Akira M. Matsui - Doutorando Universidade de Tokyo</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/8776038016041917" target="_blank">Straus Michalsky - Doutorando IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/2605934447999302" target="_blank">José Teodoro - Mestrando IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/3693915918069542" target="_blank">André Luís de Lima - Mestrando FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/3965923523593288" target="_blank">Lucas Santos de Oliveira - Mestre IMEUSP</a> </small></li>
          <li><small><a href="http://lattes.cnpq.br/3873062445569152" target="_blank">Victor Williams Stafusa da Silva – Mestre IMEUSP</a></small> </li>
          <li><small><a href="http://lattes.cnpq.br/2350572278366806" target="_blank">Carlos Leonardo Herrera Muñoz - Mestrando IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/2452505576333369" target="_blank">Edith Zaida Sonco Mamani - Mestranda IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/2605934447999302" target="_blank">José Teodoro - Mestrando IMEUSP</a> </small></li>
          <li><small><a href="http://lattes.cnpq.br/0284126139128849" target="_blank">Laécio Freitas Chaves – Mestrando IMEUSP</a></small></li>
          <li><small><a href="#" target="_blank">André Jin Teh Chou – Graduando FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Amanda Stenghel - Graduanda FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/8774245848990982" target="_blank">Ana Clara de Souza Santana - Graduanda FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/3715644973427707" target="_blank">Bhakta Krpa das Santos - Graduando FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Bruna Sellin Trevelin - Graduanda Direito USP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/0523942267027344" target="_blank">Diogo Augusto - Graduando em IC FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/1387924249101546" target="_blank">Enzo Toshio S. L. de Mello - FITO</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/7486437542826471" target="_blank">Fernanda Adams Domingos - Graduanda FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/1930659530940141" target="_blank">Gabriela Previdello Ferreira Orth - Doutoranda ECAUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/5529848738416350" target="_blank">Guilherme A. Nogueira Cesar - Graduando FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/6605070572538127" target="_blank">Giuliano Salvatore Fiusa Magnelli - Graduando FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/7968062612895669" target="_blank">Ilka Apocalypse Jóia Paulini - Graduanda FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Isabella Mendonça - Graduanda IMEUSP</a></small></li>
          <li><small><a href="#" target="_blank">Jéssica Maria Neves Lúcio - Graduanda FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Joel Marques – Graduando FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Karina Silva de Souza - Graduanda FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Kevin Ritschel - Graduando FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/7328149928743537" target="_blank">Lucas Caracik - Graduando em IC FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/0908900989509491" target="_blank">Luciana Molinari Monteforte - Graduanda FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Marina de Souza Barbosa Ferreira - Graduanda ECAUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/1280093125646759" target="_blank">Martim Passos - Graduando FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Nita Napoleão Encola - Graduanda ECAUSP</a></small></li>
		  <li><small><a href="#" target="_blank">Renato Massao Maeda - Graduando IMEUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/0223428961992896" target="_blank">Renato Veras de Paiva - Graduando ECAUSP</a></small></li>
          <li><small><a href="#" target="_blank">Rodrigo Ciorciari Salandim - Graduando EACHUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/8004254725476493" target="_blank">Ruth Cuiá Troncarelli - Graduanda FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/9088835359158147" target="_blank">Samuel Carvalho G. Fukumoto - Graduando FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/6238515721855943" target="_blank">Tatiana Kuchar – Graduanda FAUUSP</a></small></li>
          <li><small><a href="http://lattes.cnpq.br/1506056214423087" target="_blank">Thaísa Peres Takeyama Miyahara – Graduanda FAUUSP</a></small></li>
          <li><small><a href="#" target="_blank">Valdeci Antônio dos Santos - Graduando ECAUSP</a></small></li>
        </ul>
        <br>
        
        <h3>Parceiros de desenvolvimento</h3>
        <ul>
			<li><small><a href="http://www.wikimedia.org/" target="_blank">Alexandre Hannud Abdo - Wikimedia</a></small></li>
			<li><small><a href="http://www.rckt.com.br/" target="_blank">André Chou - RCKT</a></small></li>
			<li><small><a href="http://www.bench.com.br/" target="_blank">Jean Pierre Chamouton - Benchmark Design Total</a></small></li>
			<li><small><a href="http://www.rckt.com.br/" target="_blank">Pedro Emilio Guglielmo - RCKT</a></small></li>
			<li><small><a href="http://www.brzcomunicacao.com.br/" target="_blank">Tiago Ortlieb - BRZ Comunicação</a></small></li>
        </ul>
      </div>
      <!--   FIM - COLUNA DIREITA   -->
      
    </div>
    <!--   FIM - MEIO DO SITE-->

@stop
