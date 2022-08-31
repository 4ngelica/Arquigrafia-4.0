@extends('layouts.default')

@section('head')

<title>Arquigrafia - Seu universo de imagens de arquitetura</title>

@stop

<body>
	<!--   #CONTAINER   -->
	<div id="container">


@stop

@section('content')

		<?php //include "includes/header.php"; ?>

		<!--   GROUP   -->
		<div class="container">
    	<div class="eight columns">
        
        <div id="group_header" class="eight columns alpha omega row">
       		<div class="two columns alpha">
          	<img src="{{ URL::to("/") }}/placeholders/group-cover-1.jpg">
          </div>
          <div class="three columns omega info">
            <h1>Grupo de azulejos azuis</h1>
            <p>Assim mesmo, a percepção das dificuldades prepara-nos para enfrentar situações atípicas decorrentes do sistema de participação geral.</p>
            <p>É importante questionar o quanto a revolução dos costumes auxilia a preparação e a composição de alternativas às soluções ortodoxas.</p>
  <span class="date">Criado em 11/12/2013</span>
          </div>
        </div>
        
        <div id="group_content">
          
          <!-- POSTAGENS DO GRUPO -->
          <div class="column row alpha omega">
            <form name="group_form" id="group_form" method="post" enctype="multipart/form-data" action="/photo/2778">
              <input type="hidden" name="groupMgr.entity" value="2778" />
              <input type="hidden" name="groupMgr.userId" value="21" /> 
              <div class="column alpha omega"><img class="user_thumbnail"  src="{{ URL::to("/") }}/placeholders/avatar.jpg" /></div>
              <div class="three columns">
                <strong><a href="#" id="name">Pedro Emilio Guglielmo</a></strong><br>
                Deixe seu comentário
                <textarea id="comment_field" name="groupMgr.text"></textarea>
                <input type="submit" id="comment_button" class="cursor btn" value="COMENTAR" />
              </div>
            </form>
          </div>
          
          <div class="column row alpha omega group_entry">
            <div class="column alpha omega"><img class="user_thumbnail"  src="{{ URL::to("/") }}/placeholders/avatar-3.jpg" /></div>
            <div class="three columns">
            <strong><a href="#" id="name">Lucas Caracik Lero Amet</a></strong><br>
            <p>No entanto, não podemos esquecer que o desenvolvimento contínuo de distintas formas de atuação garante a contribuição de um grupo importante na determinação do remanejamento dos quadros funcionais.</p>
            <p>Do mesmo modo, a consolidação das estruturas não pode mais se dissociar das novas proposições.</p>
            </div>
          </div>
          
          <div class="column row alpha omega group_entry">
            <div class="column alpha omega"><img class="user_thumbnail"  src="{{ URL::to("/") }}/placeholders/avatar-2.jpg" /></div>
            <div class="three columns">
              <strong><a href="#" id="name">Lucas Caracik Lero Amet</a></strong><br>
              <p>No entanto, não podemos esquecer que o desenvolvimento contínuo de distintas formas de atuação garante a contribuição de um grupo importante na determinação do remanejamento dos quadros funcionais.</p>
              <p>Do mesmo modo, a consolidação das estruturas não pode mais se dissociar das novas proposições.</p>
            </div>
          </div>
          
          
        </div>
      </div>
      	
      <!-- USUARIOS NO GRUPO -->
      <div class="four columns row">
        <hgroup class="profile_block_title">
          <h3><i class="group"></i> Usuários no grupo (13)</h3>
          <a href="#" class="small profile_block_link">Ver todos</a>
        </hgroup>
        <!--   BOX - AMIGOS   -->
        <div class="profile_box">
          <div>
            <a href="user.php" class="friend_photo"><img title="Fernando Gobbo" src="{{ URL::to("/") }}/profile/b554beaa-1b5d-4d6f-b40d-b039fa9219e6_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Fernando Gobbo</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="João Francisco" src="{{ URL::to("/") }}/profile/128-2.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Ilka</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Ruth Cuiá Troncarelli" src="{{ URL::to("/") }}/profile/c2982c9b-ed00-4185-9dc4-513aca4a55f4_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Ruth Cuiá Troncarelli</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Fabiana" src="{{ URL::to("/") }}/profile/bb9c9f2f-6527-4cd3-a91d-d96115ecf5b0_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Fabiana</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Straus" src="{{ URL::to("/") }}/profile/9f4ffc8a-c912-4d15-a021-bda37e3a08e9_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">straus</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Fernanda" src="{{ URL::to("/") }}/profile/a2f7570f-b235-4622-8402-7f6cf00013c0_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Fernanda</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Lívia Perez" src="{{ URL::to("/") }}/profile/5df3d2dd-b2fd-4791-bd5b-c0511d135117_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Lívia Perez</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Eliana De Azevedo Marques" src="http://profile.ak.fbcdn.net/hprofile-ak-prn1/27337_100001010303440_2938_s_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Eliana De Azevedo Marques</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Fernando Gobbo" src="{{ URL::to("/") }}/profile/b554beaa-1b5d-4d6f-b40d-b039fa9219e6_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Fernando Gobbo</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Ilka" src="{{ URL::to("/") }}/profile/c6d5f0e2-d3fc-4ab8-843e-28a3877c7a4d_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Ilka</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Raquel Silva" src="{{ URL::to("/") }}/profile/128-3.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Ruth Cuiá Troncarelli</span> --></a>
          </div>
          <div>
            <a href="user.php" class="friend_photo"><img title="Fabiana" src="{{ URL::to("/") }}/profile/bb9c9f2f-6527-4cd3-a91d-d96115ecf5b0_view.jpg" class="friend_photo" /><!--<span class="default_list_friend_name ">Fabiana</span> --></a>
          </div>
        </div>
      </div>
      
      <br class="clear">
      
      <!-- IMAGENS NO GRUPO -->
      <div id="group_photos" class="four columns">
        <hgroup class="profile_block_title">
          <h3><i class="photos"></i> Fotos no grupo (5)</h3>
        </hgroup>
        
        <div class="profile_box">
          <div class="gallery_box">
              <a href="single.php" class="gallery_photo">
                  <img src="{{ URL::to("/") }}/placeholders/group-photo-3.jpg" class="gallery_photo" /></a>
          </div>
          <div class="gallery_box">
              <a href="single.php" class="gallery_photo">
                  <img src="{{ URL::to("/") }}/placeholders/group-photo-2.jpg" class="gallery_photo" /></a>
          </div>
          <div class="gallery_box">
              <a href="single.php" class="gallery_photo">
                  <img src="{{ URL::to("/") }}/placeholders/group-photo-1.jpg" class="gallery_photo" /></a>
          </div>
          <div class="gallery_box">
              <a href="single.php" class="gallery_photo">
                  <img src="{{ URL::to("/") }}/placeholders/group-photo-4.jpg" class="gallery_photo" /></a>
          </div>
          <div class="gallery_box">
              <a href="single.php" class="gallery_photo">
                  <img src="{{ URL::to("/") }}/placeholders/group-photo-5.jpg" class="gallery_photo" /></a>
          </div>
        </div>
      </div>
      
      </div>
    </div>
    
    
    <!-- FOOTER -->
    <?php //include "includes/footer.php"; ?>
    
		<!--   MODAL   -->
		<div id="mask"></div>
		<div id="form_window">
			<!-- ÁREA DE LOGIN - JANELA MODAL -->
			<a class="close" href="#" title="FECHAR"></a>
			<div id="registration"></div>
		</div>

	<!--   FIM - #CONTAINER   -->
</body>
</html>

@stop