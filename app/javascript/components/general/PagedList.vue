<script>
import Pager from './Pager.vue';

export default {
  name: 'PagedList',
  components: {
    Pager,
  },
  props: {
    limit: {
      type: Number,
      required: true,
    },
  },
  methods: {
    handleChangePage(type) {
      console.log(type);
    },
  },
}
</script>

<template>
  <div>
    <Pager
      :currentPage="1"
      :numPages="10"
      :handleChangePage="type => handleChangePage(type)"
    />
  </div>
</template>

<style scoped>
  
</style>
