<script>
  export default {
    name: 'ConerRibbon',
    props: {
      text1: {
        type: String,
        required: true,
      },
      text2: {
        type: String,
      },
      link: {
        type: String,
      },
      color: {
        type: String,
      },
    },
  };
</script>

<template>
  <div class="corner-ribbon top-left sticky shadow" v-bind:class="[color ? color : 'black']">
    <a v-if="link" :href="link">
      <p>{{ text1 }}</p>
      <p v-if="text2">{{ text2 }}</p>
    </a>
    <a v-else :href="link">
      <p>{{ text1 }}</p>
      <p v-if="text2">{{ text2 }}</p>
    </a>
  </div>
</template>

<style scoped>
  /* The ribbons */
  .corner-ribbon {
    width: 550px;
    background: #e43;
    position: absolute;
    text-align: center;
    line-height: 50px;
    letter-spacing: 1px;
    padding-top: 10px;
    padding-bottom: 10px;
    z-index: 99;
  }

  p {
    margin: 0;
    padding: 0;
    line-height: 30px;
    font-size: 18px;
    color: #f0f0f0;
  }

  a {
    text-decoration: none;
  }

  /* Custom styles */
  .corner-ribbon.sticky{
    position: fixed;
  }

  .corner-ribbon.shadow{
    box-shadow: 0 0 3px rgba(0,0,0,.3);
  }

  /* Different positions */
  .corner-ribbon.top-left{
    top: 75px;
    left: -154px;
    transform: rotate(-45deg);
    -webkit-transform: rotate(-45deg);
  }

  /* Colors */
  .corner-ribbon.white{ background: #f0f0f0; color: #555; }
  .corner-ribbon.black{ background: #333; }
  .corner-ribbon.grey{ background: #999; }
  .corner-ribbon.blue{ background: #39d; }
  .corner-ribbon.green{ background: #2c7; }
  .corner-ribbon.turquoise{ background: #1b9; }
  .corner-ribbon.purple{ background: #95b; }
  .corner-ribbon.red{ background: #e43; }
  .corner-ribbon.orange{ background: #e82; }
  .corner-ribbon.yellow{ background: #ec0; }
</style>
