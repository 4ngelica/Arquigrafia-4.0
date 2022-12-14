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
        <img class="img-fluid" :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" alt="" width="100%">
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
          <h4>Suas impressões sobre {{photo.name}}</h4>
          <form @submit.prevent="submit" class="my-3">
            <div class="form-group my-4">
              <label for="knownArchitecture">Eu conheço pessoalmente esta arquitetura.</label>
              <input type="checkbox" name="knownArchitecture" v-model="knownArchitecture">

              <label for="areArchitecture">Estou no local.</label>
              <input type="checkbox" name="areArchitecture"  v-model="areArchitecture">
              <div class="col-12 d-flex flex-column my-4">

                <p class="mb-4">Para cada um dos pares abaixo, quais são as qualidades predominantes na arquitetura que são visíveis nesta imagem?</p>

                <label for="aberta" class="d-flex justify-content-between">
                  <p>Aberta ({{100 - fechada}}%)</p>
                  <p>Fechada ({{fechada}}%)</p>
                </label>
                <input type="range" name="fechada" class="form-control-range mb-4" id="fechada" v-model="fechada" min="0" max="100" step="1">

                <label for="externa" class="d-flex justify-content-between">
                  <p>Interna ({{100 - externa}}%)</p>
                  <p>Externa ({{externa}}%)</p>
                </label>
                <input type="range" name="externa" class="form-control-range mb-4" id="externa" v-model="externa" min="0" max="100" step="1">

                <label for="simples" class="d-flex justify-content-between">
                  <p>Complexa ({{100 - simples}}%)</p>
                  <p>Simples ({{simples}}%)</p>
                </label>
                <input type="range" name="simples" class="form-control-range mb-4" id="simples" v-model="simples" min="0" max="100" step="1">

                <label for="assimetrica" class="d-flex justify-content-between">
                  <p>Simétrica ({{100 - assimetrica}}%)</p>
                  <p>Assimétrica ({{assimetrica}}%)</p>
                </label>
                <input type="range" name="assimetrica" class="form-control-range mb-4" id="assimetrica" v-model="assimetrica" min="0" max="100" step="1">

                <label for="opaca" class="d-flex justify-content-between">
                  <p>Translúcida ({{100 - opaca}}%)</p>
                  <p>Opaca ({{opaca}}%)</p>
                </label>
                <input type="range" name="opaca" class="form-control-range mb-4" id="opaca" v-model="opaca" min="0" max="100" step="1">

                <label for="vertical" class="d-flex justify-content-between">
                  <p>Horizontal ({{100 - vertical}}%)</p>
                  <p>Vertical ({{vertical}}%)</p>
                </label>
                <input type="range" name="vertical" class="form-control-range mb-4" id="vertical" v-model="vertical" min="0" max="100" step="1">
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
                <img :src="'/arquigrafia-images/' + evaluation.photo_id + '_view.jpg'" class="photo-carousel">
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
      areArchitecture: '',
      formData: {
        binomial_13: '',
        binomial_14: '',
        binomial_16: '',
        binomial_19: '',
        binomial_20: '',
        binomial_21: ''
      },
      errors: [],
      success: false,
    }
  },
  methods: {
    submit() {
        this.errors = null
        this.aberta = 100 - this.fechada
        this.interna = 100 - this.externa
        this.simetrica = 100 - this.assimetrica
        this.translucida = 100 - this.opaca
        this.horizontal = 100 - this.vertical

        this.formData.binomial_13 = Math.max(this.horizontal, this.vertical);
        this.formData.binomial_14 = Math.max(this.translucida, this.opaca);
        this.formData.binomial_16 = Math.max(this.simetrica, this.assimetrica);
        this.formData.binomial_19 = Math.max(this.complexa, this.simples);
        this.formData.binomial_20 = Math.max(this.externa, this.interna);
        this.formData.binomial_21 = Math.max(this.aberta, this.fechada);

        let formData = new FormData()

        _.each(this.formData, (value, key) => {
          formData.append(key, value)
        })
        formData.append('knownArchitecture', this.knownArchitecture)
        formData.append('areArchitecture', this.areArchitecture)
        formData.append('user_id', this.$props.auth.id)

        window.axios.post('/api/evaluations/' + this.$props.photo.id + '/evaluate', formData ).then(response => {

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
  }
};

</script>
