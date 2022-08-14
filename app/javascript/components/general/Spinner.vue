<script>
  import SimpleSpinner from 'vue-simple-spinner';

  export default {
    name: 'Spinner',
    components: {
      SimpleSpinner,
    },
  }
</script>

<template>
  <SimpleSpinner :size="30" line-fg-color="#222" message="Carregando..." />
</template>
