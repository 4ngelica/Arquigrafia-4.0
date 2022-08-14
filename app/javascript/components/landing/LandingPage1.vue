<script>
  import i18n from '../../lang/i18n';
  import BottomPageStrip from './BottomPageStrip.vue';

  export default {
    name: 'LandingPage1',
    i18n,
    props: {
      handleChangeLang: {
        type: Function,
        required: true,
      },
    },
    components: {
      BottomPageStrip,
    },
  };
</script>

<template>
  <div class="fullscreen_post_bg">
    <!-- HEADER PAGINA INICIAL -->
    <div class="header-main">
      <a href="/home"><img src="/img/logoarq-header.png" /></a>
      <div class="cadastro-button"><a href="/home">Ir para o site >></a></div>
      <div class="lang-button"><a href="#" @click="handleChangeLang('en')">en</a></div>
      <div class="lang-button"><a href="#" @click="handleChangeLang('pt')">pt</a></div>
      <!-- <div class="search&#45;field"> -->
      <!--   <form id="tfnewsearch" action="/search" method="post" accept&#45;charset="UTF&#45;8" target="_blank"> -->
      <!--     <input type="text" class="tftextinput" style="color:#000" name="q" size="21" maxlength="120" value=""> -->
      <!--     <input type="submit" value="search" class="tfbutton" style="background:none;" /> -->
      <!--     <input type="hidden" value="8" name="perPage" /> -->
      <!--   </form> -->
      <!-- </div> -->
    </div>
    <!-- END HEADER PAGINA INICIAL -->
    <div id="page1" class="wrapper">
      <div class="row">
        <div style="display:flex;justify-content:center;align-items:center;text-align:center;height:100vh;">
          <div class="column-4" style="padding: 0; width: 42%;">
            <a href="/home" >
              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="540px" height="90px" viewBox="0 0 540 90" enable-background="new 0 0 540 90" xml:space="preserve">
                <g>
                  <polyline fill="none" stroke="#010101" stroke-width="3" stroke-miterlimit="10" points="34.077,45.697 44.254,45.697
                    44.254,51.563 34.077,51.563 34.077,35.919 44.254,35.919 44.254,51.563"/>
                  <polyline fill="none" stroke="#010101" stroke-width="3" stroke-miterlimit="10" points="44.255,35.736 44.255,29.498
                    26.951,29.498 26.951,58.601 50.743,58.601 50.743,23.264 20.466,23.264 20.466,64.835 57.229,64.835 57.229,46.13 57.229,17.023
                    13.976,17.023 13.976,71.074 63.72,71.074 63.72,10.784 7.492,10.784 7.492,77.312 71.658,77.312"/>
                </g>
                <text transform="matrix(1.0338 0 0 1 83.8008 56.7783)" fill="#010101" font-family="'Roboto'" font-size="67.2074">ARQUIGRAFIA</text>
                <text transform="matrix(1.0338 0 0 1 82.7573 79.3887)" fill="#010101" font-family="'Roboto'" font-size="18.1581">{{ $t('landing.titleLogo') }} </text>
              </svg>
            </a>

            <div class="buttons-inicio">
              <!--   BOTÃO CADASTRO INICIO   -->
              <a href="/users/account">
                <div class="cadastro-inicio">
                  {{ $t('landing.textBtnCount') }}
                </div>
              </a>
              <!--   END BOTÃO CADASTRO INICIO   -->

              <!--   BOTÃO LOGIN INICIO  -->
              <a href="/users/login/">
                <div class="login-inicio">
                  {{ $t('landing.textLogIn1') }}
                </div>
              </a>
              <!--  END BOTÃO LOGIN INICIO  -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="seta">
      <img src="/img/arrow-landing.png" />
    </div>

    <BottomPageStrip
      :text="$t('landing.textPage1')"
      link="#page2"
    />
  </div>
</template>
