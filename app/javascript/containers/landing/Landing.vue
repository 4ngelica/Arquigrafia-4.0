/**
* This is the main Landing component.
* It centralizes all other components.
*/

<script>
  import { mapActions } from 'vuex';
  import store from './store';
  import LandingNavigationMenu from '../../components/landing/LandingNavigationMenu.vue';
  import HeaderPage from '../../components/landing/HeaderPage.vue';
  import LandingPage1 from '../../components/landing/LandingPage1.vue';
  import LandingPage2 from '../../components/landing/LandingPage2.vue';
  import LandingPage3 from '../../components/landing/LandingPage3.vue';
  import LandingPage4 from '../../components/landing/LandingPage4.vue';
  import LandingPage5 from '../../components/landing/LandingPage5.vue';
  import LandingPage6 from '../../components/landing/LandingPage6.vue';
  import LandingPage7 from '../../components/landing/LandingPage7.vue';
  import CornerRibbon from '../../components/general/CornerRibbon.vue';

  export default {
    name: 'Landing',
    store,
    components: {
      LandingNavigationMenu,
      HeaderPage,
      LandingPage1,
      LandingPage2,
      LandingPage3,
      LandingPage4,
      LandingPage5,
      LandingPage6,
      LandingPage7,
      CornerRibbon,
    },
    methods: mapActions([
      'setCurrentLang',
    ]),
  };
</script>

<template>
  <div>
    <CornerRibbon
      text1="Arquigrafia de luto pelo"
      text2="Museu Nacional!"
      link="/photos/completeness"
      color="black"
    />
    <LandingNavigationMenu />
    <HeaderPage :handleChangeLang="(lang) => setCurrentLang({ lang })" />
    <LandingPage1 :handleChangeLang="(lang) => setCurrentLang({ lang })" />
    <LandingPage2 />
    <LandingPage3 />
    <LandingPage4 />
    <LandingPage5 />
    <LandingPage6 />
    <LandingPage7 />
  </div>
</template>

