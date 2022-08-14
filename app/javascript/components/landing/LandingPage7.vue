<script>
  import i18n from '../../lang/i18n';

  export default {
    name: 'LandingPage7',
    i18n,
  };
</script>

<template>
  <div id="page7" class="wrapper">
    <div class="row">
      <div style="display:flex;justify-content:center;align-items:center;height:100vh;">
        <div class="column-6">
          <div class="column-4" style="padding: 0; width: 66.6666667%">
            <div style="width: 75%; text-align: center;">
              <div class="title-final" style="text-align: left;">{{ $t('landing.titlePage7') }}</div>
              <div class="texto-final" style="text-align: left;">{{ $t('landing.paragraphPage7') }}</div>

              <div class="buttons-final">
                <!--   BOTÃO CADASTRO FINAL  -->
                <div class="cadastro-final">
                  <a href="/users/account">{{ $t('landing.textBtnCount') }}</a>
                </div>
                <!--   END BOTÃO CADASTRO FINAL  -->

                <div style="font-size: 20px;color:#7f7f7f;-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
                  {{ $t('landing.registerBtn') }}
                </div>

                <!--   BOTÃO LOGIN FINAL  -->
                <div class="login-final">
                  <a href="/users/login/">
                    {{ $t('landing.textLogIn1') }}
                  </a>
                </div>
                <!--  END BOTÃO LOGIN FINAL  -->

                <div class="botao-refsite">
                  <a href="/home" style="color:white;">{{ $t('landing.siteBtn')}}</a>
                </div>
                <div class="playstore">
                  <a href="https://play.google.com/store/apps/details?id=com.ionicframework.arquigrafiamobile302511&hl=pt-br" target="_blank">
                    <img src="/img/playstore.png" />
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="column-2" style="padding: 0; width: 33.3333333%; margin-top: 75px;">
            <img src="/img/app-iphone.png">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

