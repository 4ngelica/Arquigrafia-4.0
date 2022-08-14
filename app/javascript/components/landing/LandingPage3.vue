<script>
  import i18n from '../../lang/i18n';

  export default {
    name: 'LandingPage3',
    i18n,
  };
</script>

<template>
  <div id="page3" class="wrapper">
    <div class="row">
      <div style="display:flex;justify-content:center;align-items:center;text-align:center;height:100vh;">
        <div class="column-4" style="padding: 0;">
          <img src="/img/redesocial.png">
          <div class="title-redesocial">{{ $t('landing.titlePage3') }}</div>
          <div class="texto-redesocial">{{ $t('landing.paragraphPage3') }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

