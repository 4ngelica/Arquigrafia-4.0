<script>
  import PhotoGridItem from './PhotoGridItem.vue';

  export default {
    name: 'PhotoGrid',
    props: {
      photos: {
        type: Array,
        required: true,
      },
    },
    components: {
      PhotoGridItem,
    },
  };
</script>

<template>
  <div class="container">
    <container>
      <grid class="photo-grid" v-for="i in Math.ceil(photos.length / 5)">
        <grid-item v-for="photo in photos.slice((i - 1) * 5, i * 5)" size="1/5">
          <PhotoGridItem :id="photo.id" :name="photo.name" />
        </grid-item>
      </grid>
    </container>
  </div>
</template>

<style scoped>
.photo-grid {
  margin-bottom: 12px;
}
</style>
