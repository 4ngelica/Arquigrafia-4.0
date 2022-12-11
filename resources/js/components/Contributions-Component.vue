<template>
  <div class="container">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" v-on:click="changeTab(reviews, 'reviews')">Revisões</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" v-on:click="changeTab(editions, 'editions')">Edições</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active review-tab  mt-4" id="home" role="tabpanel" aria-labelledby="home-tab">
        <h2 class="fw-bold">Suas Revisões</h2>
        <ul>
          <li>Total: {{Object.keys(this.$props.reviews).length}}</li>
          <li>Aguardando: {{Object.keys(this.$props.waiting_reviews).length}}</li>
          <li>Aceitas: {{Object.keys(this.$props.accepted_reviews).length}}</li>
          <li>Recusadas: {{Object.keys(this.$props.refused_reviews).length}}</li>
        </ul>

        <h3 class="fw-bold">Filtros:</h3>
        <div class="d-flex">
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="reviews" checked v-on:click="suggestionsFilter(reviews)">
            <label class="form-check-label" for="reviews">
              Todas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="accepted_reviews" v-on:click="suggestionsFilter(accepted_reviews)">
            <label class="form-check-label" for="accepted_reviews">
              Aceitas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="refused_reviews" v-on:click="suggestionsFilter(refused_reviews)">
            <label class="form-check-label" for="refused_reviews">
              Recusadas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="waiting_reviews" v-on:click="suggestionsFilter(waiting_reviews)">
            <label class="form-check-label" for="waiting_reviews">
              Aguardando
            </label>
          </div>
        </div>
        <div v-if="suggestions" v-for="(suggestion, index) in suggestions" :key="index" class="d-flex my-2" :v-model="suggestions">
            <img v-if="suggestion.photo[0] && suggestion.user[0]" :src="'/arquigrafia-images/'+ suggestion.photo[0].id +'_home.jpg'" alt="" width="80" height="53" class="me-2 tab-image">
            <div v-if="suggestion.photo[0] && suggestion.user[0]" class="">
              <h4 class="mb-0 pb-0 fw-bold">Sua sugestão '{{suggestion.text}}' para o campo '{{attributeNames[suggestion.attribute_type]}}' foi enviada para a revisão do autor da imagem '{{suggestion.user[0].name}}'.</h4>
              <small>{{suggestion.created_at}}</small>
            </div>
        </div>
      </div>


      <div class="tab-pane fade edition-tab mt-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <h2 class="fw-bold">Suas Edições</h2>
        <ul>
          <li>Total: {{Object.keys(this.$props.editions).length}}</li>
          <li>Aguardando: {{Object.keys(this.$props.waiting_editions).length}}</li>
          <li>Aceitas: {{Object.keys(this.$props.accepted_editions).length}}</li>
          <li>Recusadas: {{Object.keys(this.$props.refused_editions).length}}</li>
        </ul>

        <h3 class="fw-bold">Filtros:</h3>
        <div class="d-flex">
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="editions" v-on:click="suggestionsFilter(editions)">
            <label class="form-check-label" for="editions">
              Todas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="accepted_editions" v-on:click="suggestionsFilter(accepted_editions)">
            <label class="form-check-label" for="accepted_editions">
              Aceitas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="refused_editions" v-on:click="suggestionsFilter(refused_editions)">
            <label class="form-check-label" for="refused_editions">
              Recusadas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="waiting_editions" v-on:click="suggestionsFilter(waiting_editions)">
            <label class="form-check-label" for="waiting_editions">
              Aguardando
            </label>
          </div>
        </div>
        <div v-if="suggestions" v-for="(suggestion, index) in suggestions" :key="index" class="d-flex my-2" :v-model="suggestions">
            <img v-if="suggestion.photo[0] && suggestion.user[0]" :src="'/arquigrafia-images/'+ suggestion.photo[0].id +'_home.jpg'" alt="" width="80" height="53" class="me-2 tab-image">
            <div v-if="suggestion.photo[0] && suggestion.user[0]" class="">
              <h4 class="mb-0 pb-0 fw-bold">Sua sugestão '{{suggestion.text}}' para o campo '{{attributeNames[suggestion.attribute_type]}}' foi enviada para a revisão do autor da imagem '{{suggestion.user[0].name}}'.</h4>
              <small>{{suggestion.created_at}}</small>
            </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

export default {
  props: ['auth', 'editions', 'reviews', 'accepted_editions', 'refused_editions', 'waiting_editions', 'accepted_reviews', 'refused_reviews', 'waiting_reviews'],
  data () {
    return {
      suggestions: this.$props.reviews,
      attributeNames: {
        1: 'Cidade',
        2: 'País',
        3: 'Descrição',
        4: 'Bairro',
        5: 'Autor da imagem',
        6: 'Estado',
        7: 'Rua',
        8: 'Nome',
        9: 'Autor do projeto',
        10: 'Data da obra'
      }
    }
  },
  methods: {
    suggestionsFilter(selected) {
      this.suggestions = selected;
    },
    changeTab(selected, id) {
      this.suggestions = selected;
      document.getElementById(id).click();
    },
  },
  mounted () {

  }
};

</script>
