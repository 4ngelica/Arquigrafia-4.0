<template>
  <div class="container">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Revisões</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Edições</button>
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
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="reviews" v-on:click="suggestionsFilter(reviews)">
            <label class="form-check-label" for="reviews">
              Todas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="accepted_reviews" checked v-on:click="suggestionsFilter(accepted_reviews)">
            <label class="form-check-label" for="accepted_reviews">
              Aceitas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="refused_reviews" checked v-on:click="suggestionsFilter(refused_reviews)">
            <label class="form-check-label" for="refused_reviews">
              Recusadas
            </label>
          </div>
          <div class="form-check me-3">
            <input class="form-check-input" type="radio" name="flexRadioDefault" id="waiting_reviews" checked v-on:click="suggestionsFilter(waiting_reviews)">
            <label class="form-check-label" for="waiting_reviews">
              Aguardando
            </label>
          </div>
        </div>
        <div v-if="suggestions" v-for="(suggestion, index) in suggestions" :key="index" class="d-flex" >
            <img v-if="suggestion.photo[0] && suggestion.user[0]" :src="'/arquigrafia-images/'+ suggestion.photo[0].id +'_home.jpg'" alt="" width="80" height="53" class="me-2 tab-image">
            <div v-if="suggestion.photo[0] && suggestion.user[0]" class="">
              <h4 class="mb-0 pb-0 fw-bold">Sua sugestão '{{suggestion.text}}' para o campo '{{suggestion.attribute_type[0].attribute_type}}' foi enviada para a revisão do autor da imagem '{{suggestion.user[0].name}}'.</h4>
              {{suggestion.attribute_type[0].attribute_type}}
              <small>{{suggestion.created_at}}</small>
            </div>
        </div>
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <h2>Suas Edições</h2>
      </div>
    </div>
  </div>



  <!-- Sua sugestão 'R. 24 de Maio, 109 - República, São Paulo - SP, 01041-001' para o campo 'Rua' na imagem 'Sesc 24_36' foi enviada para a revisão do autor da imagem 'Lero Lero FAUUSP'.
  11:12 - 03/09/2022 -->

</template>

<script>

export default {
  props: ['auth', 'editions', 'reviews', 'accepted_editions', 'refused_editions', 'waiting_editions', 'accepted_reviews', 'refused_reviews', 'waiting_reviews'],
  data () {
    return {
      'suggestions': this.$props.reviews
    }
  },
  methods: {
    suggestionsFilter(selected) {
      // var likeButton = document.querySelector('.like-button');
      // var dislikeButton = document.querySelector('.dislike-button');
      //
      // if (this.authLike) {
      //   likeButton.classList.add("d-none");
      //   dislikeButton.classList.remove("d-none");
      // }else{
      //   likeButton.classList.remove("d-none");
      //   dislikeButton.classList.add("d-none");
      // }
      this.suggestions = selected
      console.log(selected)
    },
  },
  mounted () {
    console.log(Object.keys(this.$props.reviews).length, Object.keys(this.$props.editions).length, Object.keys(this.$props.accepted_editions).length, Object.keys(this.$props.refused_editions).length)
    console.log(this.$props.accepted_reviews)
  }
};

</script>
