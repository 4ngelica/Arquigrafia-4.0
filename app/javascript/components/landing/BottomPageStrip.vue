<script>
  import { scrollToElement } from '../../services/PageManipulator';

  export default {
    name: 'BottomPageStrip',
    props: {
      text: {
        type: String,
        required: true,
      },
      link: {
        type: String,
        required: true,
      },
    },
    methods: {
      pressedBottomPage(event) {
        event.preventDefault();
        const elementId = $('#bottom-page').children().attr('href');
        scrollToElement(elementId);
      },
    },
  };
</script>

<template>
  <div id="bottom-page" @click="event => pressedBottomPage(event)">
    <a :href="link">{{ text }}</a>
  </div>
</template>

<style lang="scss" scoped>
#bottom-page {
  -webkit-transform: translateZ(0) translateY(0);
  transform: translateZ(0) translateY(0);
  width: 100%;
  box-sizing: border-box;
  left: 0;
  top: 0;
  margin: auto;
  min-width: inherit;
  height: 60px;
  padding: 10px;
  text-align: center;
  align-content: center;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: -60px;
  background: rgba(178,178,178,0.5);
  -webkit-font-smoothing: antialiased;
  font-size: 20px;
}

#bottom-page:hover {
  cursor: pointer;
  background: rgba(178,178,178,0.7);
}

#bottom-page a {
  color: #fff;
}
</style>
