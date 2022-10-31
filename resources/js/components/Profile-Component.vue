<template>
  <div class="">
    <div class="container user-header">
      <div class="d-flex">
        <img v-if="user.photo" class="px-2" :src="user.photo" alt="" width="80" height="100">
        <img v-else class="px-2" src="/img/avatar-48.png" alt="" width="100" height="100">
        <h1 class="px-2">{{user.name}}</h1>
      </div>
      <div class="p-2">
        Seguir
      </div>
    </div>
    <div class="container-fluid">
      Fotos carousel
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
          <h3>Seguidores</h3>
          <hr>
          seguidores
        </div>
        <div class="following mb-2">
          <h3>Seguindo</h3>
          <hr>
          Seguindo
        </div>
      </div>
    </div>
    <div class="container px-2">
      <h3>Albuns</h3>
      <hr>
      Albuns
    </div>
    <div class="container px-2">
      <h3>Imagens interpretadas</h3>
      <hr>
      Imagens
    </div>
  </div>

</template>

<script>

const count = 20;

export default {
  props: ['user'],
  data () {
    return {
      followers: [],
      following: [],
      badges: [],
      albuns: [],
      photos: [],
      evaluations: []
    }
  },
  methods: {
    get () {
      console.log(this.user);

      fetch(`/images/${id}`)
      .then((res) => {
        return res.json();
      })
      .then(data => {
        // this.photos.push(...data);
        // this.loading = false;
      })
      .catch(err => {
        // this.loading = false;
      });
    },
    loadMore() {
    },
  },
  mounted () {
    this.get();
  }
};

</script>
