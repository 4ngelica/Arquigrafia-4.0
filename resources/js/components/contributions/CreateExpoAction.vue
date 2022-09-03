/**
* This is the action to create a Exposition
*/

<script>
  export default {
    name: 'CreateExpoAction',
    props: {
      handleCreateExpo: {
        type: Function,
        required: true,
      },
    },
  }
</script>

<template>
  <div>
    <div class="button create" @click="handleCreateExpo()">
      <span>Criar Exposição</span>
    </div>
  </div>
</template>

<style scoped>
.button {
  float: left;
  padding: 15px 15px 15px 35px;
  border-radius: 10px;
  margin-right: 5px;
  position: relative;
}

.button:hover {
  background-size: 20px 20px;
  transition: 0.2s linear;
  cursor: pointer;
}

.button.create {
  background: url(/img/add-button-inside-black-circle.png) left 10px center no-repeat #eee;
  background-size: 20px 20px;
}

.button.create:hover {
  background: url(/img/add-button-inside-black-circle.png) left 10px center no-repeat #ccc;
  background-size: 20px 20px;
}
</style>
