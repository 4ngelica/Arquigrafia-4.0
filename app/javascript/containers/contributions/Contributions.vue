/**
* This is the main Contributions component.
* It centralizes all other components.
*/

<script>
  import { mapActions } from 'vuex';
  import Tabs from '../../components/general/Tabs.vue';
  import TabContent from '../../components/general/TabContent.vue';
  import ReviewsContent from './ReviewsContent.vue';
  import ModerationContent from './ModerationContent.vue';
  import CuratorshipContent from './CuratorshipContent.vue';
  import EditionsContent from './EditionsContent.vue';
  import store from './store';
  import { tabProps, getTabById } from '../../services/ContributionsTabsService';
  import { getFilterById } from '../../services/ContributionsFiltersService';

  /** Exporting Vue Component */
  export default {
    name: 'Contributions',
    store,
    components: {
      Tabs,
      TabContent,
      ReviewsContent,
      ModerationContent,
      CuratorshipContent,
      EditionsContent,
    },
    props: {
      currentUser: {
        type: Object,
        required: true,
      },
      isGamefied: {
        type: Boolean,
        required: true,
      },
      selectedTab: {
        type: String,
      },
      selectedFilterId: {
        type: String,
      },
    },
    data() {
      return {
        tabProps,
        store,
      };
    },
    created() {
      this.setCurrentUser({ currentUser: this.currentUser });
      this.setGamefied({ isGamefied: this.isGamefied });
      // Selecting tab on start
      if (this.selectedTab === 'reviews' || this.selectedTab === 'editions') {
        const tab = getTabById(this.selectedTab);
        this.changeTab(tab);

        // Selecting filter on start
        if (this.selectedFilterId && getFilterById(this.selectedFilterId) !== null) {
          const filter = getFilterById(this.selectedFilterId);
          this.setSelectedFilter({ filter, type: this.selectedTab });
        }
      }
    },
    methods: mapActions([
      'setCurrentUser',
      'changeTab',
      'setGamefied',
      'setSelectedFilter',
    ]),
  };
</script>

<template>
  <div id="container">
    <Tabs
      :tabProps="tabProps"
      :selectedTab="store.state.selectedTab"
      :changeTab="changeTab"
    />
    <TabContent
      :selectedTab="store.state.selectedTab"
    >
      <ReviewsContent key="reviews" :active="store.state.selectedTab === 'reviews'" />
      <EditionsContent key="editions" :active="store.state.selectedTab === 'editions'" />
      <ModerationContent key="moderation" :active="store.state.selectedTab === 'moderation'" />
      <CuratorshipContent key="curatorship" :active="store.state.selectedTab === 'curatorship'" />
    </TabContent>
  </div>
</template>
