<script>
  export default {
    name: 'HeaderPage',
    props: {
      handleChangeLang: {
        type: Function,
        required: true,
      },
    },
  };
</script>

<template>
  <div class="header-page" id="header-pagina">
    <a href="/home" >
      <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         width="260px" height="45px" viewBox="0 0 260 45" enable-background="new 0 0 260 45" xml:space="preserve">
        <g>
          <polyline fill="none" stroke="#010101" stroke-width="2" stroke-miterlimit="10" points="15.419,24.032 20.63,24.032 20.63,27.037
            15.419,27.037 15.419,19.026 20.63,19.026 20.63,27.037 	"/>
          <polyline fill="none" stroke="#010101" stroke-width="2" stroke-miterlimit="10" points="20.631,18.932 20.631,15.737
            11.769,15.737 11.769,30.641 23.954,30.641 23.954,12.544 8.448,12.544 8.448,33.833 27.275,33.833 27.275,24.254 27.275,9.349
            5.124,9.349 5.124,37.029 30.599,37.029 30.599,6.153 1.804,6.153 1.804,40.224 34.665,40.224 	"/>
        </g>
        <!-- <text transform="matrix(1.0338 0 0 1 42.2119 27.9512)" fill="#010101" font&#45;family="'Roboto'" font&#45;size="24.453">ARQUIGRAFIA</text> -->
        <!-- <text transform="matrix(1.0338 0 0 1 41.708 39.5527)" fill="#010101" font&#45;family="'Roboto'" font&#45;size="8.7682">Seu universo de imagens de arquitetura </text> -->
      </svg>
    </a>

    <div class="buttons">
      <!--   BOTÃO DA BARRA DE BUSCA   -->
      <div class="search-field">
        <form id="tfnewsearch" action="/search" method="post" accept-charset="UTF-8" target="_blank">
          <input type="text" class="tftextinput" style="color:#000" name="q" size="21" maxlength="120" value="">
          <input type="submit" value="search" class="tfbutton" style="background:none;" />
          <input type="hidden" value="8" name="perPage" />
        </form>
      </div>
      <!--   END BOTÃO DA BARRA DE BUSCA   -->

      <!--   BOTÃO CADASTRO SUPERIOR  -->
      <div class="cadastro-button">
        <a href="/users/account">Criar uma conta</a>
      </div>
      <!--   END BOTÃO CADASTRO SUPERIOR  -->

      <!--   BOTÃO LOGIN SUPERIOR  -->
      <div class="login-button">
        <a href="/users/login/">Log in</a>
      </div>
      <!--  END BOTÃO LOGIN SUPERIOR  -->

      <!-- BOTÕES LINGUAS -->
      <div class="lang-button"><a href="javascript:void(0)" @click="handleChangeLang('en')">en</a></div>
      <div class="lang-button"><a href="javascript:void(0)" @click="handleChangeLang('pt')">pt</a></div>
      <!-- END BOTÕES LINGUAS -->
    </div>
  </div>
</template>
