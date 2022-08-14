/**
 * VUEX STORE FOR PHOTOS COMPLETENESS
 */

import Vue from 'vue';
import Vuex from 'vuex';
import { getCompletenessPhotos } from '../../services/PhotosService';

Vue.use(Vuex);

// Root state object
const initialState = {
  isLoadingPhotos: false,
  photos: [],
};

const mutations = {
  setPhotos(storeState, { photos }) {
    storeState.photos = photos;
  },
  setIsLoadingPhotos(storeState, { loading }) {
    storeState.isLoadingPhotos = loading;
  },
};

const actions = {
  getPhotosToComplete({ commit }) {
    // Setting that is loading photos
    commit('setIsLoadingPhotos', { loading: true });

    getCompletenessPhotos().then((response) => {
      // Setting photos object
      const { photos } = response;
      // Setting photos
      commit('setPhotos', { photos });
      // Setting we've finish loading
      commit('setIsLoadingPhotos', { loading: false });
    });
  },
};

export default new Vuex.Store({
  state: initialState,
  actions,
  mutations,
});
