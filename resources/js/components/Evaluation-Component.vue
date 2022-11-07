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
            <span>Inserido em: {{photo.created_at}}</span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
              </svg>0</span>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-dots" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg>0</span>
          </div>
        </div>
        <img class="img-fluid" :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" alt="" width="100%">
        <div class="d-flex flex-column flex-md-row my-3">
          <div class="col-12 col-md-2">
            <button type="button" name="button">Voltar</button>
          </div>
          <div v-if="auth" class="col-12 col-md-8 d-flex justify-content-md-center">
            ahsdushudh
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
        <!-- Photo information -->
        <div class="info mb-2">
          <h4>Interpretação da {{photo.name}} realizada por {{user.name}}:</h4>
        </div>
      </div>
    </div>

    Similiar evaluations
    <div class="container px-2">
      <h3>Imagens interpretadas com média similar</h3>
    </div>
  </div>

</template>

<script>

export default {
  props: ['photo', 'auth', 'user'],
  data () {
    return {
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

      })
      .catch(err => {
      });
    },
  },
  mounted () {
    // this.get();
  }
};

</script>
