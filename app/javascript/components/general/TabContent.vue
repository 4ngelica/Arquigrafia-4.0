/**
* Content that goes behind tab
*/

<script>
export default {
  name: 'tabContent',
  props: {
    selectedTab: {
      type: String,
      required: true,
    },
  },
}
</script>

<template>
  <div class="tab-content">
    <slot></slot>
  </div>
</template>
