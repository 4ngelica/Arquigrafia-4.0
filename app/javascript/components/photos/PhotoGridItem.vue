<script>
  export default {
    name: 'PhotoGridItem',
    props: {
      id: {
        type: Number,
        required: true,
      },
      name: {
        type: String,
        required: true,
      },
    },
  };
</script>

<template>
  <div class="photo-grid-item">
    <a :href="`/photos/${id}`">
      <img
        class="photo-grid-image" :src="`/arquigrafia-images/${id}_home.jpg`"
        :data-id="id"
      />
    </a>
    <div class="overlay">
      <p>{{ name }}</p>
    </div>
  </div>
</template>

<style scoped>
.photo-grid-image {
  width: 100%;
}

a {
  text-decoration: none !important;
}

.overlay {
  display: none;
}

.photo-grid-item:hover .overlay {
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  background: rgba(0,0,0,0.7);
  color: #fff;
  display: inline-block;
  -webkit-transition: 0.5s;
  -moz-transition: 0.5s;
  -o-transition: 0.5s;
  -ms-transition: 0.5s;
}

.overlay p {
  padding: 8px;
  margin: 0;
}

.photo-grid-item {
  position: relative;
  width: 100%;
  text-align: left;
  font-size: 11px;
}

a.hovertext:hover:after, a.hovertext:focus:after {
  opacity: 1.0;
} 
</style>
