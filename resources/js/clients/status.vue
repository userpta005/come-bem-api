<template>
  <span class="dot" style="background-color: #008b51;" v-if="status == 'A'"></span>
  <span class="dot" style="background-color: #df0000;" v-if="status == 'B'"></span>
  <span class="dot" style="background-color: #000000;" v-if="status == 'C'"></span>
  <span class="dot" style="background-color: #5b5b5b;" v-if="status == 'E'"></span>
  <span class="dot" style="background-color: #009dda;" v-if="status == 'P'"></span>
  <span class="dot" style="background-color: #000287;" v-if="status == 'R'"></span>
  <span class="dot" style="background-color: #410079;" v-if="status == 'S'"></span>
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