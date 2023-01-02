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
              <i id="likes"></i>
              <small class="d-flex pe-2"> {{this.photo_likes}}</small>
              <i id="comments"></i>
              <small class="d-flex"> {{this.comments.length ? this.comments.length : 0}}</small>
          </div>
        </div>
        <img class="img-fluid" :src="'/arquigrafia-images-scenario4/' + photo._id + '_view.webp'" alt="" width="100%">
        <div class="d-flex flex-column flex-md-row my-3">
          <div class="col-12 col-md-2">
            <button type="button" name="button">Voltar</button>
          </div>
          <div v-if="auth" class="col-12 col-md-8 d-flex justify-content-md-center">
            <ul class="single_view_image_buttons d-flex justify-content-around p-0">
              <li class="album-button px-2"><a href="#" title="Adicione aos seus álbuns" data-bs-toggle="modal" data-bs-target="#staticBackdrop"></a> </li>
              <li class="download-button px-2"><a :href="'/arquigrafia-images/'+ photo.id + '_view.jpg'" :download="photo._id + '_view.jpg'" title="Faça o download" target="_blank"></a></li>
              <li class="evaluate-button px-2"><a :href="'/evaluations/'+ photo.id +'/evaluate'" title="Registre suas impressões sobre" ></a></li>
              <li class="like-button px-2" v-on:click="like()"><a href="#" title="Curtir"></a></li>
              <li class="dislike-button px-2" v-on:click="dislike()"><a href="#" title="Dislike"></a></li>
              <li class="denounce-button px-2"><a href="#" title="Denunciar imagem"></a></li>
            </ul>
          </div>
          <div v-else="auth" class="col-12 col-md-8">
            Faça o <a href="/users/login">login</a> para fazer o download e comentar as imagens.
          </div>
          <div class="col-12 col-md-2 d-flex justify-content-md-end">
            <ul class="single_view_social_network_buttons d-flex p-0 justify-content-around">
              <li class="twitter"><a href="#"></a></li>
              <li class="google"><a href="#"></a></li>
              <li class="facebook"><a href="#"></a></li>
            </ul>
          </div>
        </div>
        <div class="tags">
          <h3 class="fw-bold">Tags:</h3>
          <ul :v-if="tags">
            <li v-for="(tag, index) in tags" :key="index"> {{tag.name}}</li>
          </ul>
        </div>
        <div class="comments">
          <h3 class="fw-bold pb-4">Comentários</h3>
          <form v-if="auth" @submit.prevent="addComment">
            <div class="d-flex flex-wrap">
              <img v-if="auth.photo" :src="auth.photo" alt="" width="48" height="48">
              <img v-else src="/img/avatar-48.png" alt="" width="48" height="48">
              <div class="px-2">
                <h3 class="d-flex fw-bold">{{auth.name}}</h3>
                <label for="exampleFormControlTextarea1" class="form-label col-12"> Deixe seu comentário</label>
              </div>
            </div>
            <textarea class="form-control my-2" id="exampleFormControlTextarea1" rows="3" v-model="formData.text"></textarea>
            <button type="submit" name="button">COMENTAR</button>
            <br>
            <small>Cada usuário é responsável por seus próprios comentários. O Arquigrafia não se responsabiliza pelos comentários postados, mas apenas por tornar indisponível no site o conteúdo considerado infringente ou danoso por determinação judicial (art.19 da Lei 12.965/14).</small>
          </form>
          <span v-else>Faça o <a href="/users/login">Login</a> e comente sobre {{photo.name}}</span>
          <div v-if="this.comments.length > 0">
            <div v-for="(comment, index) in comments" :key="index" class="d-flex flex-wrap my-4">
              <img v-if="comment.user && comment.user.photo" :src="comment.user.photo" alt="" width="60" height="60">
              <img v-else src="/img/avatar-48.png" alt="" width="48" height="48">
              <div class="px-2 d-flex flex-column">
                <div class="d-flex">
                  <h3 v-if="comment.user" >{{comment.user.name}}</h3>
                  <small v-if="comment.dataUpload">- {{comment.dataUpload}}</small>
                </div>
                <p>{{comment.text}}</p>
              </div>
              <div class="d-flex ms-auto">
                <button v-if="auth" class="ml-auto me-2" type="button" name="button" v-on:click="likeComment(comment, index)">Curtir</button>
                <button v-if="auth._id == comment.user._id" class="ml-auto" type="button" name="button" v-on:click="deleteComment(comment, index)">Deletar</button>
              </div>
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
            <h3 class="px-2 d-flex">{{user.name}}</h3>
            <a class="px-2" href="#">Seguir</a>
          </div>
        </div>
        <!-- Photo information -->
        <div class="info my-4">
          <div class="d-flex border-bottom">
            <i></i>
            <h3>Informações</h3>
          </div>
          <h4 v-if="photo.description">Descrição:</h4>
          <p v-if="photo.description">{{photo.description}}</p>

          <h4 v-if="photo.collection">Coleção:</h4>
          <p v-if="photo.collection">{{photo.collection}}</p>

          <h4 v-if="photo.imageAuthor">Autor(es) da Imagem:</h4>
          <p v-if="photo.imageAuthor">{{photo.imageAuthor}}</p>

          <h4 v-if="photo.dataCriacao">Data da Imagem:</h4>
          <p v-if="photo.dataCriacao">{{photo.dataCriacao}}</p>

          <h4 v-if="photo.project_author">Autor(es) do Projeto:</h4>
          <p>{{photo.project_author}}</p>

          <h4 v-if="photo.workDate">Data de conclusão da obra:</h4>
          <p v-if="photo.workDate">{{photo.workDate}}</p>

          <h4 v-if="photo.city || photo.state || photo.street || photo.country || photo.district">Endereço:</h4>
          <p v-if="photo.street && photo.district && photo.city" class="mb-0">{{photo.street}}, {{photo.district}} - {{photo.city}}</p>
          <p v-else-if="photo.street && photo.district && !photo.city" class="mb-0">{{photo.street}}, {{photo.district}}</p>
          <p v-else-if="photo.street && !photo.district && photo.city" class="mb-0">{{photo.street}} - {{photo.city}}</p>
          <p v-else-if="!photo.street && photo.district && photo.city" class="mb-0">{{photo.district}} - {{photo.city}}</p>
          <p v-else-if="photo.street && !photo.district && !photo.city" class="mb-0">{{photo.street}}</p>
          <p v-else-if="!photo.street && !photo.district && photo.city" class="mb-0">{{photo.city}}</p>
          <p v-else="!photo.street && !photo.district && !photo.city" class="mb-0">{{photo.district}}</p>

          <p v-if="photo.state && photo.country" class="mb-0">{{photo.state}} - {{photo.country}}</p>
          <p v-else-if="photo.state && !photo.country" class="mb-0">{{photo.state}}</p>
          <p v-else="!photo.state && photo.country" class="mb-0">{{photo.country}}</p>


          <div v-if="photo.institution_id" class="institution mt-4">
            <h4>Essas informações foram definidas por {{institution.name}}.</h4>
            <p>Se você tem alguma informação adicional sobre esta imagem, por favor, envie um email para {{institution.email}}</p>
          </div>

          <div v-if="photo.institution_id == null && photo.type !== 'video'" class="institution mt-4">
            <div class="modal-wrapper">
              <div class="title2 fw-bold">Você conhece mais informações sobre esta arquitetura?</div>
              <div class="title1 mb-2">
                <p>Por exemplo: {{suggestion_fields.incompleteFieldsString}}?</p>
              </div>
              <div class="modal-button OpenModal mb-4 w-100">
                <a v-if="auth" href="#" data-origin="button" data-bs-toggle="modal" data-bs-target="#contributions">Ajude a completar dados!</a>
                <a v-else href="/users/login" data-origin="button">Faça o <a href="/users/login">login</a> e contribua com mais informações sobre esta imagem!</a>
              </div>
            </div>
          </div>

          <h4 class="mb-2">Licença:</h4>
          <div class="w-100">
            <a class="tooltip_license"
              :href="'http://creativecommons.org/licenses/'+ license[0] +'/3.0/deed.pt_BR'" target="_blank" >
              <img :src="'/img_scenario4/' + license[0] + '88x31.webp'" id="ccicons"
                alt="Creative Commons License" />
              <span>
                <strong v-if="user.id == auth.id">Você é proprietário(a) desta imagem</strong>
                <strong>O proprietário desta imagem:</strong>
                <p>"{{license[1]}}"</p>
              </span>
            </a>
          </div>


          <h4 class="mt-4">Localização:</h4>
          <GmapMap
                :center='center'
                :zoom='12'
                style='width:100%;  height: 250px;'
                :position="center"
              />
       </GmapMap>

          <h4 class="mt-4">Interpretações da arquitetura:</h4>
          <img src="/img_scenario4/GraficoFixo.webp"/>
          <div class="border">
            <a class="w-100" :href="'/evaluations/'+ photo._id +'/evaluate'" v-if="auth" style="text-align: justify;">
              Seja o primeiro a registrar impressões sobre {{photo.name}}
            </a>
          </div>
          <a class="w-100 d-none" :href="'/evaluations/'+ photo._id +'/evaluate'" v-if="auth" style="text-align: justify;">
            Seja o primeiro a registrar impressões sobre {{photo.name}}
          </a>
          <p v-else style="text-align: justify;">
            Faça o <a href="/users/login">Login</a> e seja o primeiro a registrar impressões sobre a Fachada de casa
          </p>
        </div>
      </div>
    </div>

    <!-- Similiar evaluations -->
    <!-- <div class="container px-2">
      <h3>Imagens interpretadas com média similar</h3>
      <span v-if="auth">comentariossss</span>
      <span v-else>Faça o <a href="users/login">Login</a> e comente sobre a Residência do arquiteto Paulo Mendes da Rocha</span>
    </div> -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Adicione aos seus álbuns</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" v-on:click="clear()"></button>
          </div>
          <div class="modal-body">
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="contributions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Sugestões</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="suggestions-modal" action="index.html" method="post">
              <div class="" v-if="suggestionsForm" v-for="(suggestionField, index) in suggestionsForm" :key="index">
                <div class="d-flex flex-column">
                  <label v-if="suggestionField.information && suggestionStep == index" :for="suggestionField.field">{{suggestionField.information}}</label>
                  <input v-if="suggestionField.information && suggestionStep == index" type="text" :name="suggestionField.field" :placeholder="suggestionField.name" v-model="suggestionsForm[index].value">
                </div>

                <div class="d-flex flex-column">
                  <label v-if="suggestionField.validation && suggestionStep == index" :for="suggestionField.field">{{suggestionField.validation}}</label>
                  <input v-if="suggestionField.validation && suggestionStep == index" type="text" :name="suggestionField.field" :placeholder="suggestionField.name" v-model="suggestionsForm[index].value">
                </div>

                <div v-if="suggestionStep == index" class="d-flex my-4">
                  <button v-if="index > 0" type="button" class="btn btn-primary me-auto" v-on:click="changeSuggestionStep('previous',index)">
                    Anterior
                  </button>

                  <button v-if="index + 1 < suggestionsForm.length" type="button" class="btn btn-primary ms-auto" v-on:click="changeSuggestionStep('next', index)">
                    Próximo
                  </button>

                  <button v-if="suggestionsForm.length == index + 1" type="button" class="btn btn-primary ms-auto" v-on:click="submitSuggestionForm()">
                    Enviar
                  </button>
                </div>
              </div >
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

let mapsKey = process.env.MIX_GOOGLE_MAPS_KEY;
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.use(VueGoogleMaps, {
  load: {
    key: mapsKey,
    libraries: 'places',
  }
});

export default {
  props: ['photo', 'auth', 'user', 'tags', 'likes', 'auth_like', 'lat_lng', 'license', 'suggestion_fields', 'institution'],
  data () {
    return {
      photo_likes: this.$props.likes,
      authLike: this.$props.auth_like,
      comments: [],
      suggestionsForm: this.$props.suggestion_fields.formData,
      center: { lat: this.$props.lat_lng[0], lng: this.$props.lat_lng[1] },
      formData: {
        text: ''
      },
      suggestionStep: 0
    }
  },
  methods: {
    getLikes () {
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
    getComments() {
      window.axios.get("/api/comments/" + this.$props.photo._id).then((response) => {
        if(response.data){
          response.data.forEach((item, i) => {
            this.comments.push(item);
          });
        }

      }).catch((error) => {
        console.log('erro')
      });

    },
    like() {
      var likeButton = document.querySelector('.like-button');
      var dislikeButton = document.querySelector('.dislike-button');

      window.axios.get('/like/' + this.$props.photo._id).then(response => {
        this.authLike = 1;
        this.photo_likes++;
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
    addComment() {

      let formData = new FormData()
      formData.append('text', this.formData.text)
      formData.append('user_id', this.$props.auth._id)


      window.axios.post('/api/comments/' + this.$props.photo._id, formData).then(response => {
        this.comments.push(response.data);
      }).catch(err => {

      });
    },
    deleteComment(comment, index) {
      window.axios.delete('/api/comments/' + comment._id,     {
        'Access-Control-Allow-Origin': '*',
        'Content-type': 'application/json',
    }).then(response => {
        this.comments.splice(index, 1);
      }).catch(err => {

      });
    },
    submitSuggestionForm (){
      let formData = new FormData()

      formData.append('user_id', this.$props.auth._id)

      _.each(this.suggestionsForm, (value, key) => {
        if (value.information) {
          formData.append(value.field, JSON.stringify([value.value, 'edition', value.attribute_type]))
        }

        if (value.validation) {
          formData.append(value.field, JSON.stringify([value.value, 'review', value.attribute_type]))
        }

      })




      window.axios.post('/api/suggestions/' + this.$props.photo._id + '/store', formData).then(response => {

      }).catch(err => {

      });
    },
    changeSuggestionStep(action, index){
      if(action == 'next'){
        return this.suggestionStep = index + 1;
      }

      if(action == 'previous'){
        return this.suggestionStep = index - 1;
      }
    }
  },
  mounted () {
    if (this.$props.auth) {
      this.getLikes();
    }

    this.getComments();
  }
};

</script>
