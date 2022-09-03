<script>
  export default {
    name: 'ContributionsFilters',
    props: {
      filterItems: {
        type: Array,
        required: true,
      },
      handleSelectFilter: {
        type: Function,
        required: true,
      },
      selectedFilter: {
        type: Object,
        required: true,
      },
    },
  };
</script>

<template>
  <div class="container">
    <p class="title">Filtros:</p>
    <div class="radio-box">
      <div class="radio-container" v-for="filterItem in filterItems">
        <input
          type="radio"
          :id="filterItem.id"
          @click="handleSelectFilter(filterItem)"
          :checked="filterItem.id === selectedFilter.id"
        />
        <label for="radio">{{ filterItem.name }}</label>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  padding-top: 10px;
  padding-bottom: 10px;
}

.radio-box {
  display: flex;
  flex-direction: row;
}

.title {
  font-weight: bold;
  margin: 0;
  padding: 0;
}

.radio-container {
  margin-right: 10px;
}
</style>

