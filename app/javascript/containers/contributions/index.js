/**
 * This file is the connection between the HTML page and the VUE components.
 * This is the file that will be the enter to bundle with Webpack.
 */

import Vue from 'vue';
import Contributions from './Contributions.vue';

/**
 * In this function we create the Vue component for Contributions
 * @return  The vue component
 */
const createContributionsComponent = (currentUser, isGamefied, selectedTab, selectedFilterId) => (
  new Vue({
    el: '#contributions-content',
    data: {
      currentUser,
      isGamefied,
      selectedTab,
      selectedFilterId,
    },
    template: '<Contributions :currentUser="currentUser" :isGamefied="isGamefied" :selectedTab="selectedTab" :selectedFilterId="selectedFilterId" />',
    components: { Contributions },
  })
);

/**
 * There are some global variables that comes from PHP server side, which are:
 * @param  {Object}  currentUser  The current user logged in
 * @param  {Boolean}  isGamefied  If it's rendering (or not) the gamefied page
 */
$(document).ready(() => {
  // When document is ready, we create the Vue component for page
  createContributionsComponent(currentUser, isGamefied, selectedTab, selectedFilterId); // eslint-disable-line
});
