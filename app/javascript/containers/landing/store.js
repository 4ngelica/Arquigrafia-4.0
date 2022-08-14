/**
 * VUEX STORE FOR LANDING PAGE
 */

import Vue from 'vue';
import Vuex from 'vuex';
import { changeLanguage } from '../../lang/i18n';

Vue.use(Vuex);

const initialState = {
  selectedLang: null,
};

const mutations = {
  setCurrentLang(storeState, { lang }) {
    storeState.selectedLang = lang;
    changeLanguage(lang);
  },
};

const actions = {
  setCurrentLang({ commit }, { lang }) {
    commit('setCurrentLang', { lang });
  },
};

export default new Vuex.Store({
  state: initialState,
  actions,
  mutations,
});

