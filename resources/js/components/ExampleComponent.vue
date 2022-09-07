<template>
  <div class="hero is-fullheight is-bold is-info">
  <div class="hero-body">
    <!-- <div class="container"> -->
      <div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="20">
        <div class="image-grid">

          <div class="image-item" v-for="photo in photos">
            <a :href="photo.name" target="_blank" />
            <img :src="'/arquigrafia-images/' + photo.id + '_home.jpg'" alt="">
            <div class="overlay">
              <div class="download">&#43;</div>
            </div>
            </a>
          </div>
        </div>
      </div>
      <img v-if="loading === true" id="loader-img" src="" />
    <!-- </div> -->
  </div>
  </div>
</template>

<script>
import infiniteScroll from 'vue-infinite-scroll'

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
        if(! this.loading){
          this.loading = true;

          fetch(`/images/${count}`)
          .then((res) => {
            return res.json();
          })
          .then(data => {
            this.photos.push(...data);
            this.loading = false;
          })
          .catch(err => {
            this.loading = false;
          });
        }
      }
    }
  };

</script>
