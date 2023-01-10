<template>
  <span class="dot" style="background-color: green;" v-if="status == 1"></span>
  <span class="dot" style="background-color: red;" v-if="status == 2"></span>
</template>

<script setup>
const props = defineProps(['status'])
</script>

<style scoped>
.dot {
  height: 16px;
  width: 16px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
}
</style>
