<template>
  <div class="user-profile">
    <div class="container-fluid mb-4">
      <div class="container">
        <h1>Álbum {{album.title}}</h1>
      </div>
      <carousel v-if="photos.length > 0" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:true}, 1000:{items:6,  dots:true} }">
          <div v-for="(photo, index) in photos" :key="index" class="image-item">
            <a :href="'/photos/' + photo.photo_id">
              <img :src="'/arquigrafia-images/' + photo.photo_id + '_view.jpg'" :alt="photo.photo[0].name" class="photo-carousel">
              <div class="overlay"><p class="image-title">{{photo.photo[0].name}}</p></div>
            </a>
          </div>
      </carousel>
    </div>
    <div class="container mb-4">
        <div class="d-flex ">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
            <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
            <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
          </svg>
          <h3>Informações</h3>
        </div>
        <hr>
        <ul class="user-attributes list-group">
          <li>Autor: {{ user.name + ' ' + (user.lastName ? user.lastName : '') }} </li>
          <li>Título: {{ album.title }} </li>
          <li>Descrição: {{ album.description ?  album.description : ''}} </li>
        </ul>
    </div>
    <div class="container mb-4">
      <div class="d-flex flex-row align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
          <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
          <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
        </svg>
        <h3>Outros albums de {{user.name}}</h3>
      </div>
      <hr>
        <carousel v-if="other_albums.length > 0" :margin="5" :nav="false" :responsive="{1:{items:1.5, dots:false},600:{items:3, dots:true}, 1000:{items:5,  dots:true} }">
            <div v-for="(other_album, index) in other_albums" :key="index" class="image-item">
              <a v-if="other_album.cover_id" :href="'/albums/' + other_album._id">
                <img :src="'/arquigrafia-images/' + other_album.cover_id + '_view.jpg'" :alt="other_album.title" class="photo-carousel">
                <div class="overlay"><p class="image-title">{{other_album.title}} ({{other_albums_photos[other_album.id].elements.length}})</p></div>
              </a>
              <a v-else="other_album.cover_id" :href="'/albums/' + other_album._id">
                <p class="album-without-cover">Album sem capa</p>
              </a>
            </div>
        </carousel>
        <p v-else-if="auth._id == user._id">  Você ainda não tem nenhum álbum. Crie um <a href="#">aqui</a></p>
        <p v-else>O usuário ainda não possui albums.</p>
    </div>
  </div>

</template>

<script>

import carousel from 'vue-owl-carousel'

export default {
  props: ['user', 'auth', 'photos', 'album', 'other_albums', 'other_albums_photos'],
  components: { carousel },
  data () {
    return {
    }
  },
  methods: {

  },
  mounted () {
    console.log(this.$props.other_albums_photos)

  }
};

</script>
