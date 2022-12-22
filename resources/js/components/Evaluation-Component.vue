<template>
  <div class="">
    <div class="container d-flex flex-column flex-md-row justify-content-between">

      <!-- Left column -->
      <div class="photo-display col-md-8 col-12">
        <div class="d-flex flex-column flex-md-row">
          <div class="col-12 col-md-8">
            <h1>{{photo.name}}</h1>
          </div>
        </div>
        <img class="img-fluid" :src="'/arquigrafia-images-scenario4/' + photo._id + '_view.webp'" alt="" width="100%">
        <div class="d-flex flex-column flex-md-row my-3">
          <div class="col-12 col-md-2">
            <button type="button" name="button">Voltar</button>
          </div>
          <div v-if="auth" class="col-12 col-md-8 d-flex justify-content-md-center">
              <ul class="single_view_image_buttons d-flex">
                <li><a href="#" title="Adicione aos seus álbuns"></a> </li>
                <li><a href="#" title="Faça o download" class="download" target="_blank"></a></li>
                <li><a href="#" title="Registre suas impressões sobre" ><span class="button_evaluate"></span> </a></li>
                <li><a href="#" class="like_button" title="Curtir"></a></li>
                <li><a href="#" title="Denunciar imagem"></a></li>
              </ul>
          </div>
          <div v-else="auth" class="col-12 col-md-8">
            Faça o <a href="/users/login">login</a> para fazer o download e comentar as imagens.
          </div>
          <div class="col-12 col-md-2 d-flex justify-content-md-end">
            <ul id="single_view_social_network_buttons">
              <li><a href="#" class="google addthis_button_google_plusone_share"><span class="google"></span></a></li>
              <li><a href="#" class="facebook addthis_button_facebook"><span class="facebook"></span></a></li>
              <li><a href="#" class="twitter addthis_button_twitter"><span class="twitter"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="tags">
          <h3>Tags</h3>
          <ul :v-if="tags">
            <li v-for="(tag, index) in tags" :key="index"> {{tag.name}}</li>
          </ul>
        </div>
      </div>

      <!-- Right column -->
      <div class="sidebar col-md-4 col-12 px-2">
        <!-- Photo information -->
        <div class="info mb-2">
          <h4>Interpretação da {{photo.name}} realizada por {{user.name}}:</h4>
          <form @submit.prevent="submit" class="my-3">
            <div class="form-group my-4">
              <label for="knownArchitecture">Eu conheço pessoalmente esta arquitetura.</label>
              <input type="checkbox" name="knownArchitecture" v-model="formData.knownArchitecture">

              <label for="areArchitecture">Estou no local.</label>
              <input type="checkbox" name="areArchitecture"  v-model="formData.areArchitecture">
              <div class="col-12 d-flex flex-column my-4">

                <p class="mb-4">Para cada um dos pares abaixo, quais são as qualidades predominantes na arquitetura que são visíveis nesta imagem?</p>

                <label for="aberta" class="d-flex justify-content-between">
                  <p>Aberta ({{100 - formData.fechada}}%)</p>
                  <p>Fechada ({{formData.fechada}}%)</p>
                </label>
                <input type="range" name="fechada" class="form-control-range mb-4" id="fechada" v-model="formData.fechada" min="0" max="100" step="1">

                <label for="externa" class="d-flex justify-content-between">
                  <p>Interna ({{100 - formData.externa}}%)</p>
                  <p>Externa ({{formData.externa}}%)</p>
                </label>
                <input type="range" name="externa" class="form-control-range mb-4" id="externa" v-model="formData.externa" min="0" max="100" step="1">

                <label for="simples" class="d-flex justify-content-between">
                  <p>Complexa ({{100 - formData.simples}}%)</p>
                  <p>Simples ({{formData.simples}}%)</p>
                </label>
                <input type="range" name="simples" class="form-control-range mb-4" id="simples" v-model="formData.simples" min="0" max="100" step="1">

                <label for="assimetrica" class="d-flex justify-content-between">
                  <p>Simétrica ({{100 - formData.assimetrica}}%)</p>
                  <p>Assimétrica ({{formData.assimetrica}}%)</p>
                </label>
                <input type="range" name="assimetrica" class="form-control-range mb-4" id="assimetrica" v-model="formData.assimetrica" min="0" max="100" step="1">

                <label for="opaca" class="d-flex justify-content-between">
                  <p>Translúcida ({{100 - formData.opaca}}%)</p>
                  <p>Opaca ({{formData.opaca}}%)</p>
                </label>
                <input type="range" name="opaca" class="form-control-range mb-4" id="opaca" v-model="formData.opaca" min="0" max="100" step="1">

                <label for="vertical" class="d-flex justify-content-between">
                  <p>Horizontal ({{100 - formData.vertical}}%)</p>
                  <p>Vertical ({{formData.vertical}}%)</p>
                </label>
                <input type="range" name="vertical" class="form-control-range mb-4" id="vertical" v-model="formData.vertical" min="0" max="100" step="1">
              </div>


              <!-- _token: rFFGjAN7ZNLgW7ETRQDdsQJCQXGnCT5uyApWGCrw
              value-21: 50
              value-20: 50
              value-19: 50
              value-16: 50
              value-14: 50
              value-13: 50 -->
            </div>
            <button type="submit" class="btn btn-primary my-4 mx-1">Enviar</button>
            <div v-if="success" class="alert alert-success mt-3">
                Interpretação enviada com sucesso!
            </div>
        </form>
        </div>
      </div>
    </div>

    <div class="container px-2">
      <h3>Imagens interpretadas com média similar</h3>
        <carousel v-if="evaluations.length > 0" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:true}, 1000:{items:5,  dots:true} }">
            <div v-for="(evaluation, index) in evaluations" :key="index">
              <a :href="'/photos/' + evaluation.photo_id">
                <img :src="'/arquigrafia-images-scenario4/' + evaluation.photo_id + '_200h.webp'" class="photo-carousel">
              </a>
            </div>
        </carousel>
    </div>
  </div>

</template>

<script>

export default {
  props: ['photo', 'auth', 'user', 'tags'],
  data () {
    return {
      evaluations: [],
      formData: {
        aberta: 50,
        fechada: 50,
        interna: 50,
        externa: 50,
        complexa: 50,
        simples: 50,
        simetrica: 50,
        assimetrica: 50,
        translucida: 50,
        opaca: 50,
        horizontal: 50,
        vertical: 50,
        knownArchitecture: '',
        areArchitecture: ''
      },
      errors: [],
      success: false,
    }
  },
  methods: {
    submit() {
        this.errors = null
        this.formData.aberta = 100 - this.formData.fechada
        this.formData.interna = 100 - this.formData.externa
        this.formData.complexa = 100 - this.formData.simples
        this.formData.simetrica = 100 - this.formData.assimetrica
        this.formData.translucida = 100 - this.formData.opaca
        this.formData.horizontal = 100 - this.formData.vertical

        let formData = new FormData()

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })

        console.log(formData)

        window.axios.post('/api/users/', formData ).then(response => {

        }).catch(err => {
          if (err.response.status === 422) {
            this.errors = []
            _.each(err.response.data.errors, error => {
              _.each(error, e => {
                this.errors.push(e)
              })
            })
          }
        });
      },
  },
  mounted () {
    console.log(this.$props.evaluation)
  }
};

</script>
