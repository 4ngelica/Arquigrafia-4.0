<template>
  <div class="">
    <div class="container user-header">
      <div class="d-flex">
        <img class="px-2" :src="'/arquigrafia-avatars/4882.jpg'" alt="" width="80" height="100">
        <h1 class="px-2">NOME DO USER</h1>
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
          <li>Nome: </li>
        </ul>
      </div>
      <div class="user-social col-md-4 col-12 px-2">
        <div class="conquistas mb-2">
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
  data () {
    return {
      photos: [],
      loading: false
    }
  },
  methods: {
    loadMore() {
    },
  }
};

</script>
