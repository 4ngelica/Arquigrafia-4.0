/**
* This component holds a list item with a Image and a Text.
* Can be use to give a "notification" style to the list.
*/

<script>
export default {
  name: 'ItemNotificationImageText',
  props: {
    imageURL: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      required: true,
    },
    clickableURL: {
      type: String,
      default: null,
    },
    date: {
      type: String,
      required: true,
    },
  },
}
</script>

<template>
  <li>
    <div class="container">
      <a v-if="clickableURL != null" :href="clickableURL" target="_blank">
        <img class="mini-photo" :src="imageURL">
        <div class="text-container">
          <span class="text">{{ text }}</span>
          <span class="date">{{ date }}</span>
        </div>
      </a>
      <div v-else>
        <div>
          <img class="mini-photo" :src="imageURL">
          <span class="text">{{ text }}</span>
        </div>
      </div>
    </div>
  </li>
</template>

<style scoped>
  .container {
    float: left;
    margin-bottom: 10px;
  }

  .text-container {
    display: flex;
    flex-direction: column;
  }

  .text {
    font-size: 14px;
    font-weight: bold;
    color: #888;
  }

  .date {
    font-size: 12px;
    color: #ABABAB;
    margin-top: 3px;
  }

  .mini-photo {
    float: left;
    width: 80px;
    margin-right: 10px;
  }
</style>