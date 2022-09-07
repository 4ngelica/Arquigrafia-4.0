<template>
  <div class="hero is-fullheight is-bold is-info">
  <div class="hero-body">
    <div class="container">
      <div class="header content">
        <h2 class="subtitle is-6">Code Challenge #16</h2>
        <h1 class="title is-1">
          Infinite Scroll Unsplash Code Challenge
        </h1>
      </div>
      <div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="20">
        <div class="image-grid">
          <div class="image-item" v-for="photo in photos" :style="{ 'background-color': photo.color }">
            <a :href="photo.links.download" target="_blank" />
            <img :src="photo.urls.regular">
            <div class="overlay">
              <div class="download">&#43;</div>
            </div>
            </a>
          </div>
        </div>
      </div>
      <img v-if="loading === true" id="loader-img" src="https://res.cloudinary.com/chuloo/image/upload/v1550093026/scotch-logo-gif_jq4tgr.gif" />
    </div>
  </div>
</div>

<!-- <div class="hero is-fullheight is-bold is-info">
<div class="hero-body">
  <div class="container">
    <div class="header content">
      <h2 class="subtitle is-6">Code Challenge #16</h2>
      <h1 class="title is-1">
        Infinite Scroll Unsplash Code Challenge
      </h1>
    </div>
    <div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="20">
      <div class="image-grid">

        <div class="image-item" v-for="photo in photos">
          <a :href="photo.name" target="_blank" />
          <img src="http://arquigrafia.org.br/arquigrafia-images/1522_view.jpg">
          <div class="overlay">
            <div class="download">&#43;</div>
          </div>
          </a>
        </div>
      </div>
    </div>
    <img v-if="loading === true" id="loader-img" src="https://res.cloudinary.com/chuloo/image/upload/v1550093026/scotch-logo-gif_jq4tgr.gif" />
  </div>
</div>
</div> -->


        <!-- <div class="grid">
          <div v-infinite-scroll="loadMore" infinite-scroll-disabled="loading" infinite-scroll-distance="50">
            <div class="image-grid">
              <div class="image-item" v-for="photo in photos">
                  <img src="http://arquigrafia.org.br/arquigrafia-images/1522_view.jpg">
              </div>
            </div>
          </div>
          <img v-if="loading === true" id="loader-img" src="https://res.cloudinary.com/chuloo/image/upload/v1550093026/scotch-logo-gif_jq4tgr.gif" />
        </div> -->
</template>

<script>
import infiniteScroll from 'vue-infinite-scroll'

  const apiRoot = "https://api.unsplash.com";
  const accessKey =  "afa83606113a74d2e1a55e289f05fa0ca4caedf39bfc390ec4dfc39e4275891a";
  const count = 5;

  export default {
    data () {
    return {
      photos: [],
      loading: false,
      count: 5,
      }
    },
    methods: {
      loadMore() {
        if(! this.loading){
          this.loading = true;
          // window.axios.get("/api/photos?fields=id,name&random=" + this.count).then((response) => {
          //   this.photos.push(response.data);
          //   this.loading = false;
          //
          //   // alert("Please put in your name");
          // }).catch((error) =>{
          //   console.log(error);
          //     this.loading = false;
          // })
  	  	  fetch(`${apiRoot}/photos/random?client_id=${accessKey}&count=${count}`)
          .then((res) => {
            return res.json();
          })
          .then(data => {
            console.log((data));

            console.log(...data);
            this.photos.push(...data);
            this.loading = false;
          })
          .catch(err => {
            console.log(err);
            this.loading = false;
          });
          // fetch(`/images/${count}`)
          // .then((res) => {
          //   return res.json();
          // })
          // .then(data => {
          //   // console.log((data));
          //
          //   this.photos.push(data);
          //   this.loading = false;
          // })
          // .catch(err => {
          //   console.log(err);
          //   this.loading = false;
          // });
        }
      }
    }
  };

</script>
