/**
* A Table component
*/

<script>
export default {
  name: 'FormTable',
  props: {
    columns: {
      type: Array,
      required: true,
    },
  },
}
</script>

<template>
  <table class="form-table table" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th
          v-for="column in columns"
          :key="column"
        >
          {{ column }}
        </th>
      </tr>
    </thead>
    <tbody>
      <slot />
    </tbody>
  </table>
</template>

<style>
.form-table.table th {
  text-align: left;
  line-height: 30px;
  padding:0 5px 0 3px;
}
</style>
