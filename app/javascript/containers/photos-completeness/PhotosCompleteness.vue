<script>
  import { mapActions } from 'vuex';
  import PhotoGrid from '../../components/photos/PhotoGrid.vue';
  import Pager from '../../components/general/Pager.vue';
  import Spinner from '../../components/general/Spinner.vue';
  import store from './store';
  export default {
    name: 'PhotosCompleteness',
    store,
    components: {
      PhotoGrid,
      Pager,
      Spinner,
    },
    methods: mapActions([
      'getPhotosToComplete',
    ]),
    data() {
      return {
        store,
      };
    },
    created() {
      this.getPhotosToComplete();
    },
  };
</script>

<template>
  <div>
    <div class="container">
      <div class="twelve columns row" style="margin-right:30px; margin-left=30px">
        <h1>Nota de repúdio</h1>
        <table bgcolor = "black" style="width:100%">
        <font color="white">
        <p style="line-height: 1.5" align="justify"> Devido aos nossos esforços para preservar a herança e cultura
        brasileiras, consideramos importante apresentar uma nota
        pública de repúdio aos cortes orçamentários que comprometeram a manutenção do Museu Nacional no
        Rio de Janeiro, levando ao incêndio que destruiu sua coleção de mais de 20
        milhões de itens no dia 2/09/2018. É também para evitar perdas dessa
          natureza que continuaremos trabalhando em colaboração.</p>
        <p style="line-height: 1.5"> </p>
        </font>
        </table>
      </div>


 <div class="twelve columns row" align="justify">
      <h1>Colabore conosco na preservação do patrimônio cultural</h1>
        <!--<p>Ajude o Arquigrafia a ser uma fonte de conhecimento sobre arquitetura e seus espaços urbanos!-->
	<p style="line-height: 1.5">Você tem imagens sobre o Museu Nacional? Compartilhe conosco!! Vamos manter a memória desse importante patrimônio!
        Você também pode colaborar completando os dados sobre as imagens.
        Clique em "Ajude a completar dados" a partir de qualquer imagem compartilhada por usuários particulares do Arquigrafia.
        Questões vão auxiliar você a completar ou revisar os dados já inseridos.
        Juntos vamos aprimorar esse acervo de mais de 12000 imagens.
        Comece agora mesmo a contribuir acessando uma imagem abaixo. Contamos com você!</p> 
      <p style="line-height: 1.5"> </p>
      </div>
    </div>
    <div v-if="store.state.isLoadingPhotos">
      <Spinner />
    </div>
    <div v-else>
      <PhotoGrid :photos="store.state.photos" />
    </div>
  </div>
</template>
