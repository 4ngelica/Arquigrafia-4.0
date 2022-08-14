/**
* This component renders a list of tabs, based on an array of tabs passed
*/

<script>
// Importing icons (Only the ones that we're using)
import 'vue-awesome/icons/lock';
// Importing components
import Icon from 'vue-awesome/components/Icon.vue'

export default {
  name: 'Tabs',
  props: {
    tabProps: {
      type: Array,
      default: [],
      required: true,
    },
    selectedTab: {
      type: String,
      default: '',
      required: true,
    },
    changeTab: {
      type: Function,
      required: true,
    },
    locked: {
      type: Boolean,
    },
  },
  components: {
    Icon,
  }
};
</script>

<template>
  <div class="tabs">
    <ul class="tabs-list">
      <li
        v-for="tab in tabProps"
        v-if="!tab.hidden"
        v-bind:key="tab.id"
        v-bind:class="{ active: selectedTab === tab.id }"
      >
        <!-- LOCKED TAB -->
        <a v-if="tab.locked" class="locked-tab" href="#">
          <span class="tab-title">{{ tab.name }}</span>
          <Icon class="lock" name="lock" />
        </a>
        <!-- UNLOCKED TAB -->
        <a v-else :href="tab.href" @click="changeTab(tab)">
          {{ tab.name }}
        </a>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.lock {
  margin-left: 5px;
}

.tabs-list:after {
  display:block;
  clear:both;
  content:'';
}

.tabs-list li {
  margin:0px 5px;
  margin-left: 0px;
  float:left;
  list-style:none;
}
.tabs-list a {
  padding:9px 15px;
  display:inline-block;
  border-radius: 3px 3px 0 0;
  -moz-border-radius: 3px 3px 0 0;
  -webkit-border-radius: 3px 3px 0 0;
  font-size:16px;
  font-weight:600;
  color:#4c4c4c;
  transition:all linear 0.15s;
  outline: none;
  border: 1px solid #ddd;
  border-bottom: none;
}

.tabs-list .locked-tab {
  color: #ddd;
  display: flex;
  flex-direction: row;
}

.tabs-list a:hover {
  border: 1px solid #777;
  border-bottom: none;
  text-decoration:none;
}
 
li.active a, li.active a:hover {
  background:#ffffff;
  color:#4c4c4c;
  border: 1px solid #999;
  border-bottom: #fff;
}
</style>

