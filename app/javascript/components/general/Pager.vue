<script>
  import OutlineButton from './OutlineButton.vue';
  export default {
    name: 'Pager',
    props: {
      currentPage: {
        type: Number,
        required: true,
      },
      numPages: {
        type: Number,
        required: true,
      },
      handleChangePage: {
        type: Function,
        required: true,
      },
    },
    methods: {
      pressedChangePage(type) {
        const numPages = this.numPages;
        let page = this.currentPage;
        if (type === 'next') {
          page += 1;
        } else if (type === 'prev') {
          page -= 1;
        } else if (type === 'last') {
          page = numPages;
        } else if (type === 'first') {
          page = 1;
        }

        this.handleChangePage(page);
      },
    },
    components: {
      OutlineButton,
    },
  }
</script>

<template>
  <div class="container">
    <OutlineButton
      v-if="currentPage > 1"
      label="&lt;&lt;"
      :handlePressButton="() => pressedChangePage('first')"
    />
    <OutlineButton
      v-if="currentPage > 1"
      label="&lt;"
      :handlePressButton="() => pressedChangePage('prev')"
    />
    <p class="pages-text">{{ currentPage }} / {{ numPages }}</p>
    <OutlineButton
      v-if="currentPage < numPages"
      label="&gt;"
      :handlePressButton="() => pressedChangePage('next')"
    />
    <OutlineButton
      v-if="currentPage < numPages"
      label="&gt;&gt;"
      :handlePressButton="() => pressedChangePage('last')"
    />
  </div>
</template>

<style scoped>
  .container {
    display: flex;
    flex-direction: row;
    height: 35px;
  }

  .pages-text {
    margin-right: 3px;
    margin-left: 3px;
  }
</style>