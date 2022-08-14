/**
 * This file is the connection between the HTML page and the VUE components.
 * This is the file that will be the enter to bundle with Webpack.
 */

import Vue from 'vue';
import Landing from './Landing.vue';

/**
 * In this function we create the Vue component for Landing
 * @return  The vue component
 */
const createLandingComponent = () => (
  new Vue({
    el: '#landing-content',
    template: '<Landing />',
    components: { Landing },
  })
);

$(document).ready(() => {
  // When document is ready, we create the Vue component for page
  createLandingComponent();
});

