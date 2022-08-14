<script>
export default {
  name: 'ItemCuratorshipExposition',
  props: {
    imageURL: {
      type: String,
      required: true,
    },
    expositionID: {
      type: Number,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      required: true,
    },
    handleEdit: {
      type: Function,
      required: true,
    },
    handleRemove: {
      type: Function,
      required: true,
    },
  },
}
</script>

<template>
  <tr class="suggestion">
    <!-- Image -->
    <td>
      <a :href="`/expositions/${expositionID}`" target="_blank">
       <img class="suggestion_photo" :src="imageURL" />
      </a>
    </td>
    <!-- Exposition Name -->
    <td class="table-suggestion-collumn">{{ name }}</td>
    <!-- Description -->
    <td class="table-suggestion-collumn">{{ description }}</td>
    <td>
      <div class="suggestion-button edit" @click="handleEdit(expositionID)">
        <span>Editar</span>
      </div>
      <div class="suggestion-button remove" @click="handleRemove(expositionID)">
        <span>Remover</span>
      </div>
    </td>
  </tr>
</template>

<style scoped>
.form-table.suggestions td {
  padding: 5px 5px 0 5px;
}

.suggestion_photo {
  width: 150px;
}

.suggestion-button {
  float: left;
  padding: 15px 15px 15px 30px;
  border-radius: 10px;
  margin-right: 5px;
  position: relative;
}

.suggestion-button a {
  text-decoration: none;
  color: inherit;
}

.suggestion-button:hover {
  background-size: 20px 20px;
  transition: 0.2s linear;
  cursor: pointer;
}

.suggestion-button.edit {
  background: url(/img/edit.png) left 7px center no-repeat #eee;
  background-size: 20px 20px;
}

.suggestion-button.edit:hover {
  background: url(/img/edit.png) left 7px center no-repeat #ccc;
  background-size: 20px 20px;
}

.suggestion-button.remove {
  background: url(/img/cancel.png) left 7px center no-repeat #eee;
  background-size: 20px 20px;
}

.suggestion-button.remove:hover {
  background: url(/img/cancel.png) left 7px center no-repeat #ccc;
  background-size: 20px 20px;
}

.table-suggestion-collumn {
  max-width: 190px;
  padding-right: 15px;
}

.new-message {
  display: flex;
  position: relative;
}

.new-message a{
  padding: 13px 3px 8px 28px;
  background: url(/img/new-message.png) left center no-repeat transparent;
  border-radius: 5px;
}

.new-message a:hover{
  border-radius: 5px;
  color: #000;
  text-decoration: underline;
}
</style>