/**
 * This file is the connection between the HTML page and the VUE components.
 * This is the file that will be the enter to bundle with Webpack.
 */

import Vue from 'vue';
import VueFractionGrid from 'vue-fraction-grid';
import PhotosCompleteness from './PhotosCompleteness.vue';

/**
 * In this function we create the Vue component for Photos Completeness
 * @return  The vue component
 */
const createPhotosCompletenessComponent = () => (
  new Vue({
    el: '#photos-completeness-content',
    template: '<PhotosCompleteness />',
    components: { PhotosCompleteness },
  })
);

$(document).ready(() => {
  // Using dependencies
  Vue.use(VueFractionGrid);
  // When document is ready, we create the Vue component for page
  createPhotosCompletenessComponent();
});

