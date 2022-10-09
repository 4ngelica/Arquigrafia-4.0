<template>
  <div class="">
    <div class="container d-flex flex-column flex-md-row justify-content-between">

      <!-- Left column -->
      <div class="photo-display col-md-8 col-12">
        <div class="d-flex flex-column flex-md-row">
          <div class="col-12 col-md-8">
            <h1>{{photo.name}}</h1>
          </div>
          <div class="col-12 col-md-4 d-flex justify-content-md-end">
            <span>Inserido em: {{photo.dataUpload}}</span>
            <span><i>i</i>0</span>
            <span><i>i</i>0</span>
          </div>
        </div>
        <img class="img-fluid" :src="'../arquigrafia-images/' + photo.id + '_view.jpg'" alt="" width="100%">
        <div class="d-flex flex-column flex-md-row">
          <div class="col-12 col-md-2">
            Voltar
          </div>
          <div v-if="auth" class="col-12 col-md-8 d-flex justify-content-md-center">
            ahsdushudh
          </div>
          <div v-else="auth" class="col-12 col-md-8">
            Faça o <a href="users/login">login</a> para fazer o download e comentar as imagens.
          </div>
          <div class="col-12 col-md-2 d-flex justify-content-md-end">
            <i>t</i>
            <i>t</i>
            <i>t</i>
          </div>
        </div>
        <div class="tags">
          <h3>Tags</h3>
          asdasdasda
        </div>
        <div class="comments">
          <h3>Comentários</h3>
          <span v-if="auth">comentariossss</span>
          <span v-else>Faça o <a href="users/login">Login</a> e comente sobre a Residência do arquiteto Paulo Mendes da Rocha</span>
        </div>
      </div>

      <!-- Right column -->
      <div class="sidebar col-md-4 col-12 px-2">
        <!-- Author information -->
        <div class="author-header mb-2">
          <div class="d-flex">
            <img class="" :src="'../arquigrafia-avatars/' + photo.id + '_view.jpg'" alt="" width="80" height="100">
            <h3 class="px-2 d-flex">{{photo.imageAuthor}}</h3>
            <a class="px-2" href="#">Seguir</a>
          </div>
        </div>
        <!-- Photo information -->
        <div class="info mb-2">
          <i></i>
          <h3 class="border-bottom" >Informações</h3>
          <h4>Descrição:</h4>
          <p>sdasdada</p>

          <h4>Coleção:</h4>
          <p>sdasdada</p>

          <h4>Autor(es) da Imagem:</h4>
          <p>sdasdada</p>

          <h4>Data da Imagem:</h4>
          <p>sdasdada</p>

          <h4>Autor(es) do Projeto:</h4>
          <p>sdasdada</p>

          <h4>Data de conclusão da obra:</h4>
          <p>sdasdada</p>

          <h4>Endereço:</h4>
          <p>sdasdada</p>

          <div class="institution">
            <h4 v-if="auth">Essas informações foram definidas pelo Acervo da Biblioteca da FAUUSP.</h4>
            <p>Se você tem alguma informação adicional sobre esta imagem, por favor, envie um email para maticon_bibfau@usp.br</p>
          </div>

          <h4>Licença:</h4>
          <p>sdasdada</p>

          <h4>Localização:</h4>
          <p>sdasdada</p>

          <h4>Interpretações da arquitetura:</h4>
          <p>sdasdada</p>
        </div>
      </div>
    </div>

    <!-- Similiar evaluations -->
    <div class="container px-2">
      <h3>Imagens interpretadas com média similar</h3>
      <span v-if="auth">comentariossss</span>
      <span v-else>Faça o <a href="users/login">Login</a> e comente sobre a Residência do arquiteto Paulo Mendes da Rocha</span>
    </div>
  </div>

</template>

<script>

export default {
  props: ['photo', 'auth'],
  data () {
    return {
      followers: [],
      following: [],
      badges: [],
      albuns: [],
      photos: [],
      evaluations: []
    }
  },
  methods: {
    get () {
      // alert(this.auth)
      // console.log(this.auth);

      fetch(`/api/photos/${this.photo.id}`)
      .then((res) => {
        return res.json();
      })
      .then(data => {
        console.log(data.user)
        // this.photos.push(...data);
        // this.loading = false;
      })
      .catch(err => {
        // this.loading = false;
      });
    },
    loadMore() {
    },
  },
  mounted () {
    this.get();
  }
};

</script>
