<template>
  <div class="container">
    <h1>Resultados encontrados para: {{ search }}</h1>
    <h2>Foram encontradas {{photoscount ? photoscount : '0'}} imagens, {{userscount ? userscount : '0'}} usuários e {{authorscount ? authorscount : '0'}} autores.</h2>
    <a href="#">Faça uma busca avançada aqui.</a>
    <a href="#">Voltar para página anterior.</a>

    <div v-if="authorscount > 0" class="results-card border my-2 p-2 rounded-2">
      <h3>Autores</h3>
      <div class="row authors">
        <ul  v-for="author in authors">
            <li>
              {{author.name }}
            </li>
        </ul>
      </div>
    </div>

    <div v-if="userscount > 0" class="results-card border my-2 p-2 rounded-2">
      <h3>Usuários</h3>
      <div class="row users">
        <div class="col-lg-2 col-md-4 col-6 d-flex flex-column justify-content-center align-items-center text-center" v-for="user in users">
          <img :src="'/arquigrafia-avatars/' + user.id + '.jpg'" alt="" @error="imageUrlAlt" width="60" height="60">
          {{user.name + ' ' + (user.lastName ? user.lastName : '') }}
        </div>
      </div>
    </div>

      <div v-if="photoscount > 0" class="results-card border my-2 p-2 rounded-2">
      <h3>Imagens</h3>
        <div class="row photos">
          <div class="col-lg-2 col-md-4 col-6 d-flex flex-column justify-content-center align-items-center text-center" v-for="photo in photos">
            <img :src="'/arquigrafia-images/' + photo.id + '_home.jpg'" alt="">
            {{photo.name}}
          </div>
        </div>
    </div>
  </div>
</template>

<script>

  export default {
    props: ['users', 'photos', 'authors', 'search', 'photoscount', 'userscount', 'authorscount'],
    methods: {
      imageUrlAlt(event) {
        event.target.src = "/img/avatar-60.png"
      }
    }
  };

</script>
