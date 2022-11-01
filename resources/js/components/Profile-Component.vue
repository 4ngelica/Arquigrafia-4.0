<template>
  <div class="user-profile">
    <div class="container user-header">
      <div class="d-flex">
        <img v-if="user.photo" class="px-2" :src="user.photo" alt="" width="80" height="100">
        <img v-else class="px-2" src="/img/avatar-48.png" alt="" width="100" height="100">
        <h1 class="px-2">{{user.name}}</h1>
      </div>
      <div class="p-2">
        <button v-on:click="follow(user.id)">Seguir</button>
        <button v-on:click="unfollow(user.id)">Deixar de seguir</button>
      </div>
    </div>
    <div class="container-fluid">
      <carousel v-if="photos.length > 0" :items="6">
          <div v-for="(photo, index) in photos" :key="index">
              <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-carousel">
          </div>
      </carousel>
    </div>
    <div class="container d-flex flex-column flex-md-row justify-content-start">
      <div class="user-profile col-md-8 col-12 px-2">
        <h3>Perfil</h3>
        <hr>
        <ul class="user-attributes list-group">
          <li>Nome completo: {{ user.name + ' ' + (user.lastName ? user.lastName : '') }} </li>
          <li v-if="user.scholarity">Escolaridade: {{ user.scholarity }} </li>
          <li v-if="user.country">País: {{ user.country }} </li>
          <li v-if="user.scholarity">Escolaridade: {{ user.scholarity }} </li>
          <li v-if="user.institution">Instituição: {{ user.institution }} </li>
          <li v-if="user.occupation">Ocupação: {{ user.occupation }} </li>
        </ul>
      </div>
      <div class="user-social col-md-4 col-12 px-2">
        <div class="conquistas mb-2 d-none">
          <h3>Conquistas</h3>
          <hr>
          Conquistas
        </div>
        <div class="followers mb-2">
          <div class="d-flex flex-row justify-content-between">
            <h3>Seguidores</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-on:click="load(followers)">
              ver todos
            </button>
          </div>
          <hr>
          <div class="d-flex flex-row">
            <a :href="'/photos/' + photo._id" v-for="(photo, index) in photos" :key="index">
              <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-followers">
            </a>
          </div>
        </div>
        <div class="following mb-2">
          <div class="d-flex flex-row justify-content-between">
            <h3>Seguindo</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" v-on:click="load(following)">
              ver todos
            </button>
          </div>
          <hr>
          <div class="d-flex flex-row">
            <a :href="'/photos/' + photo._id" v-for="(photo, index) in photos" :key="index">
              <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-following">
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="container px-2">
      <h3>Albuns</h3>
      <hr>
        <carousel v-if="photos.length > 0" :items="6">
            <div v-for="(photo, index) in photos" :key="index">
              <a :href="'/photos/' + photo._id">
                <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-carousel">
              </a>
            </div>
        </carousel>
    </div>
    <div class="container px-2">
      <h3>Imagens interpretadas</h3>
      <hr>
        <carousel v-if="photos.length > 0" :items="6">
            <div v-for="(photo, index) in photos" :key="index">
              <a :href="'/photos/' + photo._id">
                <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" :alt="photo.title" class="photo-carousel">
              </a>
            </div>
        </carousel>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel" v-if="this.followers.length > 0">Seguidores</h5>
            <h5 class="modal-title" id="staticBackdropLabel" v-if="this.following.length > 0">Seguindo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" v-on:click="clear()"></button>
          </div>
          <div class="modal-body">
            {{this.followers}}
            {{this.following}}
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
  props: ['user'],
  components: { carousel },
  data () {
    return {
      followers: [],
      following: [],
      badges: [],
      albuns: [],
      photos: [],
      evaluations: [],
      photos: []
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
    load(property) {
      console.log(property)
      property.push('teste');
    },
    clear() {
      this.followers =[];
      this.following =[];
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
    this.get();
  }
};

</script>
