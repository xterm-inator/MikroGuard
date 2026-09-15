<template>
  <div
    @mouseover="hover = true"
    @mouseleave="hover = false"
  >
    <slot v-if="hover"></slot>
    <div v-else>&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</div>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue'

const hover = ref<boolean>(false)

</script>
