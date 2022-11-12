<template>
  <div class="user-profile">
    <div class="container user-header">
      <div class="d-flex pb-2">
        <img v-if="user.photo" :src="user.photo" alt="" width="60" height="60">
        <img v-else src="/img/avatar-48.png" alt="" width="60" height="60">
        <h1 class="px-2 fw-bold">{{user.name}}</h1>
      </div>
      <div v-if="user._id !== auth._id" class="p-2">
        <button v-on:click="follow(user.id)">Seguir</button>
        <button v-on:click="unfollow(user.id)">Deixar de seguir</button>
      </div>
    </div>
    <div class="container-fluid mb-4">
      <carousel v-if="photos.length > 1" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:true}, 1000:{items:6,  dots:true} }">
          <div v-for="(photo, index) in photos" :key="index">
            <a :href="'/photos/' + photo._id">
              <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-carousel">
            </a>
          </div>
      </carousel>

      <carousel v-if="photos.length == 1" :margin="5" :nav="false" :responsive="{1:{items:1, dots:false},600:{items:3, dots:true}, 1000:{items:6,  dots:true} }">
          <div v-for="(photo, index) in photos" :key="index">
            <a :href="'/photos/' + photo._id">
              <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-carousel">
            </a>
          </div>
      </carousel>
    </div>
    <div class="container d-flex flex-column flex-md-row justify-content-start">
      <div class="user-profile col-md-8 col-12 px-2">
        <div class="d-flex align-items-center">
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
        <hr>
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
      <div class="user-social col-md-4 col-12 px-2">
        <div class="conquistas mb-2 d-none">
          <h3 class="fw-bold">Conquistas</h3>
          <hr>
          Conquistas
        </div>
        <div class="followers mb-2">
          <div class="d-flex flex-row align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill mb-1" viewBox="0 0 16 16">
              <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
              <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
              <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
            </svg>
            <h3 class="fw-bold">Seguidores</h3>
            <button type="button" class="btn btn-primary ms-auto friendship-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-on:click="load('followers')">
              ver todos
            </button>
          </div>
          <hr>
          <div class="d-flex flex-row flex-wrap">
            <a :href="'/users/' + user._id" v-for="(user, index) in followers" :key="index">
              <img v-if="user.photo" :src="user.photo" class="photo-following">
              <img v-else="user.photo"  src="/img/avatar-48.png" class="photo-followers">
            </a>
          </div>
        </div>
        <div class="following mb-2">
          <div class="d-flex flex-row align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill mb-1" viewBox="0 0 16 16">
              <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
              <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
              <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
            </svg>
            <h3 class="fw-bold">Seguindo</h3>
            <button type="button" class="btn ms-auto friendship-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-on:click="load('following')">
              ver todos
            </button>
          </div>
          <hr>
          <div class="d-flex flex-row flex-wrap">
            <a :href="'/users/' + user._id" v-for="(user, index) in following" :key="index">
              <img v-if="user.photo" :src="user.photo" class="photo-following">
              <img v-else="user.photo"  src="/img/avatar-48.png" class="photo-following">
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="albums-container px-2 mb-4">
      <div class="d-flex flex-row align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
          <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
          <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
        </svg>
        <h3 class="fw-bold">Albuns</h3>
      </div>
      <hr>
        <carousel v-if="albums.length > 1" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:true}, 1000:{items:5,  dots:true} }">
            <div v-for="(album, index) in albums" :key="index">
              <a v-if="album.cover_id" :href="'/albums/' + album._id">
                <img :src="'/arquigrafia-images/' + album.cover_id + '_view.jpg'" :alt="album.title" class="photo-carousel">
              </a>
              <a v-else="album.cover_id" :href="'/albums/' + album._id">
                <p class="album-without-cover">Album sem capa</p>
              </a>
            </div>
        </carousel>

        <carousel v-else-if="albums.length == 1" :margin="5" :nav="false" :responsive="{1:{items:1, dots:false},600:{items:3, dots:true}, 1000:{items:5,  dots:true} }">
            <div v-for="(album, index) in albums" :key="index">
              <a v-if="album.cover_id" :href="'/albums/' + album._id">
                <img :src="'/arquigrafia-images/' + album.cover_id + '_view.jpg'" :alt="album.title" class="photo-carousel">
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
      </div>
      <hr>
        <carousel v-if="evaluations.length > 1" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:true}, 1000:{items:5,  dots:true} }">
            <div v-for="(evaluation, index) in evaluations" :key="index">
              <a :href="'/evaluations/' + evaluation.photo_id + '/viewEvaluation/' + user._id">
                <img :src="'/arquigrafia-images/' + evaluation.photo_id + '_view.jpg'" class="photo-carousel">
              </a>
            </div>
        </carousel>

        <carousel v-else-if="evaluations.length == 1" :margin="5" :nav="false" :responsive="{1:{items:1, dots:false},600:{items:3, dots:true}, 1000:{items:5,  dots:true} }">
            <div v-for="(evaluation, index) in evaluations" :key="index">
              <a :href="'/evaluations/' + evaluation.photo_id + '/viewEvaluation/' + user._id">
                <img :src="'/arquigrafia-images/' + evaluation.photo_id + '_view.jpg'" class="photo-carousel">
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
            <div v-if="allFollowers" :href="'/users/' + user._id" v-for="(user, index) in allFollowers" class="d-flex">
              <img v-if="user.photo" :src="user.photo" class="photo-following">
              <img v-else="user.photo"  src="/img/avatar-48.png" class="photo-followers">
              <p>{{user.name}}</p>
              <button v-on:click="follow(user.id)" class="btn btn-primary ms-auto">Seguir</button>
              <!-- <button v-on:click="unfollow(user.id)" class="btn btn-primary ms-auto">Deixar de seguir</button> -->
            </div>
              <div v-if="allFollowing" :href="'/users/' + user._id" v-for="(user, index) in allFollowing" class="d-flex m-1">
                <img v-if="user.photo" :src="user.photo" class="photo-following">
                <img v-else="user.photo"  src="/img/avatar-48.png" class="photo-followers">
                <p>{{user.name}}</p>
                <button v-on:click="follow(user.id)" class="btn btn-primary ms-auto">Seguir</button>
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
  props: ['user', 'auth', 'photos', 'albums', 'evaluations'],
  components: { carousel },
  data () {
    return {
      followers: [],
      following: [],
      allFollowers: [],
      allFollowing: [],
    }
  },
  methods: {
    get () {
      fetch(`/images/${count}`)
      .then((res) => {
        return res.json();
      })
      .then(data => {
        this.photos.push(...data);
        // this.loading = false;
      })
      .catch(err => {
        // this.loading = false;
      });
    },
    getFollowers(query_param = '') {
      window.axios.get("/api/profile/" + this.$props.user._id + "/followers" + query_param).then((response) => {

        if(query_param){
          if(response.data.length){
            response.data.forEach((item, i) => {
              this.followers.push(item);
            });
          }
        }else{
          if(response.data.length){
            response.data.forEach((item, i) => {
              this.allFollowers.push(item);
            });
          }
        }
      }).catch((error) => {
        console.log('Erro ao buscar seguidores')
      });
    },
    getFollowing(query_param = '') {
      window.axios.get("/api/profile/" + this.$props.user._id + "/following" + query_param).then((response) => {

        if(query_param) {
          if(response.data.users.length){
            response.data.users.forEach((item, i) => {
              this.following.push(item);
            });
          }

          if(response.data.institutions.length){
            response.data.institutions.forEach((item, i) => {
              this.following.push(item);
            });
          }
        }else {
          if(response.data.users.length){
            response.data.users.forEach((item, i) => {
              this.allFollowing.push(item);
            });
          }

          if(response.data.institutions.length){
            response.data.institutions.forEach((item, i) => {
              this.allFollowing.push(item);
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
        window.axios.get("/friends/follow/" + this.$props.user._id).then((response) => {
          console.log(response);

        }).catch((error) => {
          console.log('erro')
        });
    },
    unfollow(id) {
      window.axios.get("/friends/unfollow/" + this.$props.user._id).then((response) => {
        console.log(response);

      }).catch((error) => {
        console.log('erro')
      });

    }
  },
  mounted () {
    // this.get();
    this.getFollowers('?limit=8');
    this.getFollowing('?limit=8');
  }
};

</script>
