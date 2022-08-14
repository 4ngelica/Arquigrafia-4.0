<script>
  export default {
    name: 'OutlineButton',
    props: {
      label: {
        type: String,
        required: true,
      },
      handlePressButton: {
        type: Function,
        required: true,
      },
    },
  }
</script>

<template>
  <input
    type="button"
    class="btn"
    :value="label"
    @click="handlePressButton()"
  >
</template>

<style scoped>
  .btn {
    background: #fff;
    color: #444;
    border: solid 1px #ddd;
    font-size: 10px;
    font-weight: bold;
    padding: 5px 10px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
  }

  .btn:focus {
    outline:0;
  }
  
  .btn:hover {
    color: #222;
    text-decoration: none;
    cursor: pointer;
    border-color: #aaa;
  }

  input {
    font-family: Arial, Helvetica, sans-serif;
  }
</style>
