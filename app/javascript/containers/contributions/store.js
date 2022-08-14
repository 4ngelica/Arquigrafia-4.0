/**
 * VUEX STORE FOR CONTRIBUTIONS
 */

import Vue from 'vue';
import Vuex from 'vuex';
import { getUserSuggestions, getUserSuggestionsStatistics } from '../../services/SuggestionService';
import { filterItems } from '../../services/ContributionsFiltersService';

Vue.use(Vuex);

// Root state object
const initialState = {
  selectedTab: 'reviews',
  userReviewsSuggestions: [],
  userEditionsSuggestions: [],
  reviewsCurrentSuggestionsPage: 1,
  editionsCurrentSuggestionsPage: 1,
  reviewsTotalNumSuggestionPages: 1,
  editionsTotalNumSuggestionPages: 1,
  isLoadingReviewsSuggestions: false,
  isLoadingEditionsSuggestions: false,
  isLoadingReviewsSuggestionsStatistics: false,
  isLoadingEditionsSuggestionsStatistics: false,
  reviewsSuggestionsStatistics: null,
  editionsSuggestionsStatistics: null,
  currentUser: null,
  isGamefied: false,
  selectedFilterReviews: filterItems[0], // The first filter is the selected one (at the beginning)
  selectedFilterEditions: filterItems[0], // The first filter is the selected one (at the beginning)
  filterItems,
};

// Mutations are operations that actually mutates the state.
const mutations = {
  setCurrentUser(storeState, { currentUser }) {
    storeState.currentUser = currentUser;
  },
  setGamefied(storeState, { isGamefied }) {
    storeState.isGamefied = isGamefied;
  },
  changeTab(storeState, tab) {
    storeState.selectedTab = tab.id;
  },
  acceptRejectSuggestion(storeState, { suggestionID, type }) {
    console.info('Accepting Rejecting Suggestion', suggestionID, type);
  },
  createChat(storeState, { userID }) {
    console.info('Creating chat', userID);
  },
  editExpo(storeState, { expoID }) {
    console.info('Editing expo', expoID);
  },
  removeExpo(storeState, { expoID }) {
    console.info('Remove expo', expoID);
  },
  createExpo() {
    console.info('Create Expo');
  },
  setCurrentSuggestionPage(storeState, { page, type }) {
    if (type === 'reviews') {
      storeState.reviewsCurrentSuggestionsPage = page;
    } else if (type === 'editions') {
      storeState.editionsCurrentSuggestionsPage = page;
    }
  },
  setUserSuggestions(storeState, { suggestions, type }) {
    if (type === 'reviews') {
      storeState.userReviewsSuggestions = suggestions;
    } else if (type === 'editions') {
      storeState.userEditionsSuggestions = suggestions;
    }
  },
  setTotalNumSuggestionPages(storeState, { totalNumPages, type }) {
    if (type === 'reviews') {
      storeState.reviewsTotalNumSuggestionPages = totalNumPages;
    } else if (type === 'editions') {
      storeState.editionsTotalNumSuggestionPages = totalNumPages;
    }
  },
  setIsLoadingSuggestions(storeState, { loading, type }) {
    if (type === 'reviews') {
      storeState.isLoadingReviewsSuggestions = loading;
    } else if (type === 'editions') {
      storeState.isLoadingEditionsSuggestions = loading;
    }
  },
  setIsLoadingSuggestionsStatistics(storeState, { loading, type }) {
    if (type === 'reviews') {
      storeState.isLoadingReviewsSuggestionsStatistics = loading;
    } else if (type === 'editions') {
      storeState.isLoadingEditionsSuggestionsStatistics = loading;
    }
  },
  setSuggestionsStatistics(storeState, statistics) {
    if (statistics.type === 'reviews') {
      storeState.reviewsSuggestionsStatistics = statistics;
    } else if (statistics.type === 'editions') {
      storeState.editionsSuggestionsStatistics = statistics;
    }
  },
  setSelectedFilter(storeState, { filter, type }) {
    if (type === 'reviews') {
      storeState.selectedFilterReviews = filter;
    } else if (type === 'editions') {
      storeState.selectedFilterEditions = filter;
    }
  },
};

// Actions are functions that cause side effects and can involve async ops
const actions = {
  setCurrentUser({ commit }, { currentUser }) {
    commit('setCurrentUser', { currentUser });
  },
  setGamefied({ commit }, { isGamefied }) {
    commit('setGamefied', { isGamefied });
  },
  changeTab({ commit }, tab) {
    commit('changeTab', tab);
  },
  acceptRejectSuggestion({ commit }, { suggestionID, type }) {
    commit('acceptRejectSuggestion', { suggestionID, type });
  },
  createChat({ commit }, { userID }) {
    commit('createChat', { userID });
  },
  editExpo({ commit }, { expoID }) {
    commit('editExpo', { expoID });
  },
  removeExpo({ commit }, { expoID }) {
    commit('removeExpo', { expoID });
  },
  createExpo({ commit }) {
    commit('createExpo');
  },
  getUserSuggestions({ commit, state }, { page, type }) {
    // Setting fixed limit (max number of items for each page)
    const limit = 10;
    // Setting the current page on state
    commit('setCurrentSuggestionPage', { page, type });
    // Setting that we're loading suggestions
    commit('setIsLoadingSuggestions', { loading: true, type });

    // Getting selected filter
    let filter;
    if (type === 'editions') filter = state.selectedFilterEditions;
    else if (type === 'reviews') filter = state.selectedFilterReviews;

    getUserSuggestions(page, limit, type, filter ? filter.id : undefined)
      .then((response) => {
        console.info(response);
        // Getting total number of pages
        const totalNumPages = Math.ceil(response.total_items / limit);
        // Setting total number of pages
        commit('setTotalNumSuggestionPages', { totalNumPages, type });
        // Getting suggestions array
        const { suggestions } = response;
        // Setting suggestions
        commit('setUserSuggestions', { suggestions, type });
        // Setting that we've finish to load suggestions
        commit('setIsLoadingSuggestions', { loading: false, type });
      });
  },
  getUserSuggestionsStatistics({ commit }, { type }) {
    // Setting that we're loading suggestions statistics
    commit('setIsLoadingSuggestionsStatistics', { loading: true, type });

    getUserSuggestionsStatistics(type)
      .then((response) => {
        console.info(response);
        // Setting statistics object
        const statistics = {
          type: response.type,
          total: response.num_suggestions,
          waiting: response.num_waiting_suggestions,
          accepted: response.num_accepted_suggestions,
          rejected: response.num_rejected_suggestions,
          points: response.num_points_by_type,
          total_points: response.num_total_points,
        };
        // Setting the statistics
        commit('setSuggestionsStatistics', statistics);
        // Setting that we've finish to load statistics
        commit('setIsLoadingSuggestionsStatistics', { loading: false, type });
      });
  },
  setSelectedFilter({ commit, dispatch }, { filter, type }) {
    commit('setSelectedFilter', { filter, type });
    dispatch('getUserSuggestions', { page: 1, type });
  },
};

export default new Vuex.Store({
  state: initialState,
  actions,
  mutations,
});
