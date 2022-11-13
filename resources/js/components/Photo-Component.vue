<template>
  <div class="">
    <div class="container d-flex flex-column flex-md-row justify-content-between">

      <!-- Left column -->
      <div class="photo-display col-md-8 col-12">
        <div class="d-flex flex-column flex-md-row">
          <div class="col-12 col-md-8">
            <h1 class="fw-bold">{{photo.name}}</h1>
          </div>
          <div class="col-12 col-md-4 d-flex justify-content-md-end">
            <small class="d-flex px-2">Inserido em: {{photo.dataUpload}}</small>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
              </svg>
              <small class="d-flex pe-2"> {{this.photo_likes}}</small>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-dots" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg>
              <small class="d-flex"> {{comments.length ? comments.length : 0}}</small>
          </div>
        </div>
        <img class="img-fluid" :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" alt="" width="100%">
        <div class="d-flex flex-column flex-md-row my-3">
          <div class="col-12 col-md-2">
            <button type="button" name="button">Voltar</button>
          </div>
          <div v-if="auth" class="col-12 col-md-8 d-flex justify-content-md-center">
            <ul class="single_view_image_buttons d-flex">
              <li class="album-button"><a href="#" title="Adicione aos seus álbuns"></a> </li>
              <li class="download-button"><a href="#" title="Faça o download" target="_blank"></a></li>
              <li class="evaluate-button"><a href="#" title="Registre suas impressões sobre" ></a></li>
              <li class="like-button" v-on:click="like()"><a href="#" title="Curtir"></a></li>
              <li class="dislike-button" v-on:click="dislike()"><a href="#" title="Dislike"></a></li>
              <li class="denounce-button"><a href="#" title="Denunciar imagem"></a></li>
            </ul>
          </div>
          <div v-else="auth" class="col-12 col-md-8">
            Faça o <a href="/users/login">login</a> para fazer o download e comentar as imagens.
          </div>
          <div class="col-12 col-md-2 d-flex justify-content-md-end">
            <ul class="single_view_social_network_buttons d-flex">
              <li class="google"><a href="#"></a></li>
              <li class="facebook"><a href="#"></a></li>
              <li class="twitter"><a href="#"></a></li>
            </ul>
          </div>
        </div>
        <div class="tags">
          <h3 class="fw-bold">Tags</h3>
          <ul :v-if="tags">
            <li v-for="(tag, index) in tags" :key="index"> {{tag.name}}</li>
          </ul>
        </div>
        <div class="comments">
          <h3 class="fw-bold">Comentários</h3>
          <form v-if="auth">
            <div class="d-flex flex-wrap">
              <img :src="auth.photo" alt="" width="60" height="60">
              <h3 class="px-2 d-flex">{{auth.name}}</h3>
              <label for="exampleFormControlTextarea1" class="form-label col-12"> Deixe seu comentário</label>
            </div>
            <textarea class="form-control my-2" id="exampleFormControlTextarea1" rows="3"></textarea>
            <button type="button" name="button">COMENTAR</button>
            <br>
            <small>Cada usuário é responsável por seus próprios comentários. O Arquigrafia não se responsabiliza pelos comentários postados, mas apenas por tornar indisponível no site o conteúdo considerado infringente ou danoso por determinação judicial (art.19 da Lei 12.965/14).</small>
          </form>
          <span v-else>Faça o <a href="users/login">Login</a> e comente sobre {{photo.name}}</span>
          <div v-if="comments.length > 0" v-for="(comment, index) in comments" :key="index"class="d-flex flex-wrap">
            <img src="" alt="" width="60" height="60">
            <div class="">
              <h3 class="px-2 d-flex">{{auth.name}}</h3>
              <p>{{comment.text}}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right column -->
      <div class="sidebar col-md-4 col-12 px-2">
        <!-- Author information -->
        <div class="author-header mb-2">
          <div class="d-flex">
            <a :href="'/users/' + user._id">
              <img class="" :src="user.photo" alt="" width="60" height="60">
            </a>
            <h3 class="px-2 d-flex">{{photo.imageAuthor}}</h3>
            <h3 class="px-2 d-flex">{{user.name}}</h3>
            <a class="px-2" href="#">Seguir</a>
          </div>
        </div>
        <!-- Photo information -->
        <div class="info mb-2">
          <i></i>
          <h3 class="border-bottom" >Informações</h3>
          <h4 v-if="photo.description">Descrição:</h4>
          <p v-if="photo.description">{{photo.description}}</p>

          <h4 v-if="photo.collection">Coleção:</h4>
          <p v-if="photo.collection">{{photo.collection}}</p>

          <h4 v-if="photo.imageAuthor">Autor(es) da Imagem:</h4>
          <p v-if="photo.imageAuthor">{{photo.imageAuthor}}</p>

          <h4 v-if="photo.dataCriacao">Data da Imagem:</h4>
          <p v-if="photo.dataCriacao">{{photo.imageAuthor}}</p>

          <h4 v-if="photo.project_author">Autor(es) do Projeto:</h4>
          <p>{{photo.project_author}}</p>

          <h4 v-if="photo.workDate">Data de conclusão da obra:</h4>
          <p v-if="photo.workDate">{{photo.workDate}}</p>

          <h4 v-if="photo.city || photo.state || photo.street || photo.country || photo.district">Endereço:</h4>
          <p v-if="photo.city">{{photo.city}}</p>
          <p v-if="photo.street">{{photo.street}}</p>
          <p v-if="photo.district">{{photo.district}}</p>
          <p v-if="photo.state">{{photo.state}}</p>
          <p v-if="photo.country">{{photo.country}}</p>

          <div v-if="photo.institution_id" class="institution">
            <h4>Essas informações foram definidas pelo Acervo da Biblioteca da FAUUSP.</h4>
            <p>Se você tem alguma informação adicional sobre esta imagem, por favor, envie um email para maticon_bibfau@usp.br</p>
          </div>

          <div v-if="photo.institution_id == null && photo.type !== 'video'" class="institution">
            <div class="modal-wrapper">
              <div class="title2">Você conhece mais informações sobre esta arquitetura?</div>
              <div class="title1">
                Por exemplo:
              </div>
              <div class="modal-button OpenModal">
                <a v-if="auth" href="#" data-origin="button">Ajude a completar dados!</a>
                <a v-else href="#" data-origin="button">Faça o login e contribua com mais informações sobre esta imagem!</a>
              </div>
            </div>
            <div class="modal-wrapper">
              <div class="title1">A revisão desta imagem está temporariamente bloqueada até que a análise de sugestões feitas por membros do Arquigrafia seja concluída.</div>
            </div>
            <div class="modal-wrapper">
              <div class="title2">Essas informações foram definidas por membros do Arquigrafia.</div>
              <div class="title1">
                <p style="text-align: justify;">
                  Se você tem alguma informação adicional sobre esta imagem, por favor,
                  envie um email para <a href="mailto:arquigrafiabrasil@gmail.com">arquigrafiabrasil@gmail.com</a>
                </p>
              </div>
            </div>
          </div>

          <h4>Licença:</h4>
          <a class="tooltip_license"
            :href="'http://creativecommons.org/licenses/'+ user.name +'/3.0/deed.pt_BR'" target="_blank" >
            <img :src="'img/ccIcons/' + user.name + '88x31.png'" id="ccicons"
              alt="Creative Commons License" />
            <span>
              <strong>Você é proprietário(a) desta imagem</strong>
              <strong>O proprietário desta imagem "{{photo.imageAuthor}}":</strong>
              <strong>O proprietário desta imagem "{{photo.imageAuthor}}":</strong>
              <br/>
            </span>
          </a>

          <h4>Localização:</h4>
          <!-- <div id="map_canvas" class="single_view_map" style="width:300px; height:250px;">
            <gmap-map :center="center" :zoom="12" style="width:300px;  height: 250px;">
                  <gmap-marker
                    :key="index"
                    v-for="(m, index) in markers"
                    :position="m.position"
                    @click="center=m.position"
                  ></gmap-marker>
                </gmap-map>
          </div> -->

          <h4>Interpretações da arquitetura:</h4>
          <img src="/img/GraficoFixo.png" />
        </div>
      </div>
    </div>

    <!-- Similiar evaluations -->
    <!-- <div class="container px-2">
      <h3>Imagens interpretadas com média similar</h3>
      <span v-if="auth">comentariossss</span>
      <span v-else>Faça o <a href="users/login">Login</a> e comente sobre a Residência do arquiteto Paulo Mendes da Rocha</span>
    </div> -->
  </div>

</template>

<script>

// import VueGoogleMap from 'vuejs-google-maps';
// import 'vuejs-google-maps/dist/vuejs-google-maps.css';
// var likeButton = document.querySelector('.like-button');
// var dislikeButton = document.querySelector('.dislike-button');

export default {
  props: ['photo', 'auth', 'user', 'comments', 'tags', 'likes', 'auth_like'],
  data () {
    return {
      photo_likes: this.$props.likes,
      authLike: this.$props.auth_like
    }
  },
  methods: {
    get () {
      var likeButton = document.querySelector('.like-button');
      var dislikeButton = document.querySelector('.dislike-button');

      if (this.authLike) {
        likeButton.classList.add("d-none");
        dislikeButton.classList.remove("d-none");
      }else{
        likeButton.classList.remove("d-none");
        dislikeButton.classList.add("d-none");
      }
    },
    like() {
      var likeButton = document.querySelector('.like-button');
      var dislikeButton = document.querySelector('.dislike-button');

      window.axios.get('/like/' + this.$props.photo._id).then(response => {
        this.authLike = 1;
        this.photo_likes++;
        console.log(this.photo_likes)
        likeButton.classList.add("d-none");
        dislikeButton.classList.remove("d-none");
      }).catch(err => {

      });
    },
    dislike() {
      var likeButton = document.querySelector('.like-button');
      var dislikeButton = document.querySelector('.dislike-button');

      window.axios.get('/dislike/' + this.$props.photo._id).then(response => {
        this.authLike = 0;
        this.photo_likes--;
        likeButton.classList.remove("d-none");
        dislikeButton.classList.add("d-none");
      }).catch(err => {

      });
    },
  },
  mounted () {
    this.get();
  }
};

</script>
