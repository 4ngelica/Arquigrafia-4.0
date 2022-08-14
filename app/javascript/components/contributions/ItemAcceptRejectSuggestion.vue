/**
* This item is for accepting or rejecting a suggestion
*/

<script>
export default {
  name: 'ItemAcceptRejectSuggestion',
  props: {
    photoID: {
      type: Number,
      required: true,
    },
    photoName: {
      type: String,
      required: true,
    },
    fieldName: {
      type: String,
      required: true,
    },
    user: {
      type: Object,
      required: true,
    },
    suggestionID: {
      type: Number,
      required: true,
    },
    currentFieldData: {
      type: String,
      default: null,
    },
    suggestedData: {
      type: String,
      default: null,
    },
    handleAccept: {
      type: Function,
      required: true,
    },
    handleReject: {
      type: Function,
      required: true,
    },
    handleCreateChat: {
      type: Function,
      required: true,
    }
  },
}
</script>

<template>
  <tr class="suggestion">
    <!-- Image -->
    <td>
      <a :href="`/photos/${photoID}`" target="_blank">
       <img class="suggestion_photo" :src="`/arquigrafia-images/${photoID}_home.jpg`" />
      </a>
    </td>
    <!-- Photo Name -->
    <td>{{ photoName }}</td>
    <!-- Field -->
    <td>{{ fieldName }}</td>
    <!-- Current Data -->
    <td v-if="currentFieldData" class="table-suggestion-collumn">{{ currentFieldData }}</td>
    <td v-else class="table-suggestion-collumn">-----</td>
    <!-- Suggested data -->
    <td v-if="suggestedData" class="table-suggestion-collumn">{{ suggestedData }}</td>
    <td v-else class="table-suggestion-collumn">-----</td>
    <td>
      <div class="new-message">
        <a class="create-chat-link" href="#" @click="handleCreateChat(user.id)">
          {{ user.name }}
        </a>
      </div>
    </td>
    <td>
      <div class="suggestion-button thumbs-up thumbs-link" @click="handleAccept(suggestionID)">
        <span>Aceitar</span>
      </div>
      <div class="suggestion-button thumbs-down thumbs-link" @click="handleReject(suggestionID)">
        <span>Rejeitar</span>
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

.suggestion-button.thumbs-up {
  background: url(/img/suggestions-list/thumbs-up.png) left 7px center no-repeat #eee;
  background-size: 20px 20px;
}

.suggestion-button.thumbs-up:hover {
  background: url(/img/suggestions-list/thumbs-up.png) left 7px center no-repeat #ccc;
  background-size: 20px 20px;
}

.suggestion-button.thumbs-down {
  background: url(/img/suggestions-list/thumbs-down.png) left 7px center no-repeat #eee;
  background-size: 20px 20px;
}

.suggestion-button.thumbs-down:hover {
  background: url(/img/suggestions-list/thumbs-down.png) left 7px center no-repeat #ccc;
  background-size: 20px 20px;
}

.table-suggestion-collumn {
  max-width: 200px;
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
