<template>
  <div class="hero-body">
      <div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="20">
        <div class="image-grid">
          <div class="image-item" v-for="photo in photos">
            <a :href="'photos/' + photo._id">
              <img :src="'/arquigrafia-images/' + photo._id + '_view.jpg'" alt="">
              <div class="overlay">
                <p class="image-title">{{photo.name}}</p>
              </div>
            </a>
          </div>
        </div>
      </div>
      <img v-if="loading === true" id="loader-img" src="" />
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
    props: ['institution'],
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
      },

    }
  };

</script>
