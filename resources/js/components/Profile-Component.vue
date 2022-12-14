<template>
  <div class="user-profile">
    <div class="container user-header">
      <div class="d-flex pb-2">
          <img v-if="user.photo" :src="user.photo" alt="" width="60" height="60">
          <img v-else src="/img_scenario4/avatar-48.webp" alt="" width="60" height="60">
        <h1 class="px-2 fw-bold">{{user.name}}</h1>
      </div>
      <div v-if="user._id !== auth._id && isFollowingUser == 1">
        <button v-on:click="unfollow(user.id)" class="follow-button">Deixar de seguir</button>
      </div>
      <div v-if="user._id !== auth._id && isFollowingUser == 0">
        <button v-on:click="follow(user.id)" class="follow-button">Seguir</button>
      </div>
      <div class="d-flex flex-row align-items-center my-2">
        <small class="ms-auto">Imagens compartilhadas ({{photos.length}})</small>
      </div>
    </div>
    <div class="container-fluid mb-4">
      <carousel v-if="photos.length > 1" @changed="changed" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:false}, 1000:{items:6,  dots:false} }">
          <div v-for="(photo, index) in photos" :key="index">
            <a :href="'/photos/' + photo._id">
              <img :src="'/arquigrafia-images-scenario4/' + photo._id + '_200h.webp'" :alt="photo.title" class="photo-carousel">
            </a>
          </div>
      </carousel>

      <carousel v-if="photos.length == 1" :margin="5" :nav="false" :responsive="{1:{items:1, dots:false},600:{items:3, dots:false}, 1000:{items:6,  dots:false} }">
          <div v-for="(photo, index) in photos" :key="index">
            <a :href="'/photos/' + photo._id">
              <img :src="'/arquigrafia-images-scenario4/' + photo._id + '_200h.webp'" :alt="photo.title" class="photo-carousel">
            </a>
          </div>
      </carousel>
    </div>
    <div class="container d-flex flex-column flex-md-row justify-content-start">
      <div class="user-profile col-md-4 col-12 px-2">
        <div class="d-flex align-items-center border-bottom user-profile-content-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
          </svg>
          <h3 class="fw-bold">Perfil</h3>
          <a v-if="user._id == auth._id" :href="'/users/'+ user._id +'/edit'" class="mx-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="22" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
              <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
            </svg>
          </a>
        </div>
        <ul class="user-attributes list-group">
          <li>Nome completo: {{ user.name + ' ' + (user.lastName ? user.lastName : '') }} </li>
          <li v-if="user.scholarity">Escolaridade: {{ user.scholarity }} </li>
          <li v-if="user.country">País: {{ user.country }} </li>
          <li v-if="user.scholarity">Escolaridade: {{ user.scholarity }} </li>
          <li v-if="user.institution">Instituição: {{ user.institution }} </li>
          <li v-if="user.occupation">Ocupação: {{ user.occupation }} </li>
          <!-- <li v-if="user.email && user.visibleEmail">Email: {{ user.email }} </li>
          <li v-if="user.birthday && user.visibleBirthday">Aniversário: {{ user.birthday }} </li> -->
        </ul>
      </div>

      <div class="user-profile col-md-4 col-12 px-2">
        <div class="d-flex align-items-center border-bottom user-profile-content-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill mb-1" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
          </svg>
          <h3 class="fw-bold">Seguindo ({{following_number}})</h3>
          <button type="button" class="btn ms-auto friendship-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-on:click="load('following')">
            ver todos
          </button>
        </div>
        <div class="d-flex flex-row flex-wrap">
          <a :href="'/users/' + user.id" v-for="(user, index) in following" :key="index">
            <img v-if="user.photo" :src="user.photo" class="photo-following">
            <img v-else="user.photo"  src="/img_scenario4/avatar-48.webp" class="photo-following">
          </a>
        </div>
      </div>

      <div class="user-profile col-md-4 col-12 px-2">
        <div class="d-flex align-items-center border-bottom user-profile-content-header">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill mb-1" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
          </svg>
          <h3 class="fw-bold" :v-bind="followers_number">Seguidores ({{followers_number}})</h3>
          <button type="button" class="btn btn-primary ms-auto friendship-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-on:click="load('followers')">
            ver todos
          </button>
        </div>
        <div class="d-flex flex-row flex-wrap">
          <a :href="'/users/' + user.id" v-for="(user, index) in followers" :key="index">
            <img v-if="user.photo" :src="user.photo" class="photo-followers">
            <img v-else="user.photo"  src="/img/avatar-48.png" class="photo-followers">
          </a>
        </div>
      </div>
    </div>

    <div class="albums-container px-2 my-4">
      <div class="d-flex flex-row align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
          <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
          <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
        </svg>
        <h3 class="fw-bold">Álbuns</h3>
        <small class="ms-auto">({{albums.length}})</small>
      </div>
      <hr>
        <carousel v-if="albums.length > 1" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:false}, 1000:{items:5,  dots:false} }">
            <div v-for="(album, index) in albums" :key="index">
              <a v-if="album.cover_id" :href="'/albums/' + album._id">
                <img :src="'/arquigrafia-images-scenario4/' + album.cover_id + '_200h.webp'" :alt="album.title" class="photo-carousel">
              </a>
              <a v-else="album.cover_id" :href="'/albums/' + album._id">
                <p class="album-without-cover">Album sem capa</p>
              </a>
            </div>
        </carousel>

        <carousel v-else-if="albums.length == 1" :margin="5" :nav="false" :responsive="{1:{items:1, dots:false},600:{items:3, dots:false}, 1000:{items:5,  dots:false} }">
            <div v-for="(album, index) in albums" :key="index">
              <a v-if="album.cover_id" :href="'/albums/' + album._id">
                <img :src="'/arquigrafia-images-scenario4/' + album.cover_id + '_200h.webp'" :alt="album.title" class="photo-carousel">
              </a>
              <a v-else="album.cover_id" :href="'/albums/' + album._id">
                <p class="album-without-cover">Album sem capa</p>
              </a>
            </div>
        </carousel>
        <p v-else-if="auth._id == user._id">  Você ainda não tem nenhum álbum. Crie um <a href="#">aqui</a></p>
        <p v-else>O usuário ainda não possui albums.</p>
    </div>
    <div class="evaluations-container px-2 mb-4">
      <div class="d-flex flex-row align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-bar-graph-fill" viewBox="0 0 16 16">
          <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zm.5 10v-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-2.5.5a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1zm-3 0a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-1z"/>
        </svg>
        <h3 class="fw-bold">Imagens interpretadas</h3>
        <small class="ms-auto">({{evaluations.length}})</small>
      </div>
      <hr>
        <carousel v-if="evaluatedPhotos.length > 1" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:false}, 1000:{items:5,  dots:false} }">
            <div v-for="(photo, index) in evaluatedPhotos" :key="index">
              <a :href="'/evaluations/' + photo.id + '/viewEvaluation/' + user._id">
                <img :src="'/arquigrafia-images-scenario4/' + photo.id + '_200h.webp'" class="photo-carousel">
              </a>
            </div>
        </carousel>

        <carousel v-else-if="evaluations.length == 1" :margin="5" :nav="false" :responsive="{1:{items:1, dots:false},600:{items:3, dots:false}, 1000:{items:5,  dots:false} }">
            <div v-for="(evaluation, index) in evaluations" :key="index">
              <a :href="'/evaluations/' + evaluation.photo_id + '/viewEvaluation/' + user._id">
                <img :src="'/arquigrafia-images-scenario4/' + evaluation.photo_id + '_200h.webp'" class="photo-carousel">
              </a>
            </div>
        </carousel>
        <p v-else-if="auth._id == user._id">Você ainda não realizou nenhuma avaliação. Selecione uma imagem e avalie a arquitetura apresentada nela.</p>
        <p v-else>O usuário ainda não realizou nenhuma avaliação.</p>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel" v-if="this.allFollowers.length > 0">Seguidores</h5>
            <h5 class="modal-title" id="staticBackdropLabel" v-if="this.allFollowing.length > 0">Seguindo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" v-on:click="clear()"></button>
          </div>
          <div class="modal-body">
            <div v-if="allFollowers" :href="'/users/' + user.id" v-for="(user, index) in allFollowers" class="d-flex m-1">
              <img v-if="user.photo" :src="user.photo" class="photo-following">
              <img v-else="user.photo"  src="/img_scenario4/avatar-48.webp" class="photo-followers">
              <p>{{user.name}}</p>
              <!-- <button v-on:click="follow(user.id)" class="btn btn-primary ms-auto">Seguir</button> -->
              <!-- <button v-on:click="unfollow(user.id)" class="btn btn-primary ms-auto">Deixar de seguir</button> -->
            </div>
              <div v-if="allFollowing" :href="'/users/' + user.id" v-for="(user, index) in allFollowing" class="d-flex m-1">
                <img v-if="user.photo" :src="user.photo" class="photo-following">
                <img v-else="user.photo"  src="/img_scenario4/avatar-48.webp" class="photo-followers">
                <p>{{user.name}}</p>
                <!-- <button v-on:click="follow(user.id)" class="btn btn-primary ms-auto">Seguir</button> -->
                <!-- <button v-on:click="unfollow(user.id)" class="btn btn-primary ms-auto">Deixar de seguir</button> -->
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

import carousel from 'vue-owl-carousel'

const count = 20;

export default {
  props: ['user', 'auth', 'photos', 'albums', 'evaluations', 'following_number', 'followers_number', 'is_following'],
  components: { carousel },
  data () {
    return {
      followers: [],
      following: [],
      allFollowers: [],
      allFollowing: [],
      evaluatedPhotos: [],
      isFollowingUser: this.$props.is_following
    }
  },
  methods: {
    getEvaluations () {
      window.axios.get("/api/profile/" + this.$props.user.id + "/evaluatedPhotos").then((response) => {
        // console.log((response.data.evaluations))

        response.data.evaluations.forEach((item, i) => {
          this.evaluatedPhotos.push(item.photo[0]);
        });
      }).catch((error) => {
        // console.log(error)
        console.log('Erro ao buscar fotos interpretadas')
      });
    },
    getFollowers(query_param = '') {
      window.axios.get("/api/profile/" + this.$props.user.id + "/followers" + query_param).then((response) => {

        if(query_param){
          if(response.data.users.length){
            response.data.users.forEach((item, i) => {
              this.followers.push(item.user[0]);
            });
          }
        }else{
          if(response.data.users.length){
            response.data.users.forEach((item, i) => {
              this.allFollowers.push(item.user[0]);
            });
          }
        }
      }).catch((error) => {
        console.log('Erro ao buscar seguidores')
      });
    },
    getFollowing(query_param = '') {
      window.axios.get("/api/profile/" + this.$props.user.id + "/following" + query_param).then((response) => {

        if(query_param) {
          if(response.data.users.length){
            response.data.users.forEach((item, i) => {
              this.following.push(item.user[0]);
            });
          }

          if(response.data.institutions.length){
            response.data.institutions.forEach((item, i) => {
              this.following.push(item.user[0]);
            });
          }
        }else {
          if(response.data.users.length){
            response.data.users.forEach((item, i) => {
              this.allFollowing.push(item.user[0]);
            });
          }

          if(response.data.institutions.length){
            response.data.institutions.forEach((item, i) => {
              this.allFollowing.push(item.user[0]);
            });
          }
        }
      }).catch((error) => {
        console.log('Erro ao buscar seguindo')
      });
    },
    load(property) {

        if(property == 'followers') {
          this.getFollowers();
        }

        if(property == 'following') {
          this.getFollowing();
        }
    },
    clear() {
      this.allFollowers =[];
      this.allFollowing =[];
    },
    follow(id) {
        let form = new FormData()
        form.append('user_id', this.$props.auth._id);

        window.axios.post("/api/follow/" + id, form).then((response) => {
          console.log(response);
          this.isFollowingUser = 1;
          this.followers_number + 1;

        }).catch((error) => {
          console.log('erro')
        });
    },
    unfollow(id) {
      let form = new FormData()
      form.append('user_id', this.$props.auth._id);

      window.axios.post("/api/unfollow/" + id, form).then((response) => {
        console.log(response);
        this.isFollowingUser = 0;
        this.followers_number - 1;

      }).catch((error) => {
        console.log('erro')
      });

    },
    changed() {
      console.log('oi')

    }
  },
  mounted () {
    // this.get();
    this.getFollowers('?limit=8');
    this.getFollowing('?limit=8');
    this.getEvaluations();

    // console.log(this.$props.is_following);
  }
};

</script>
