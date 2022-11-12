<template>
  <button
    :class="computedClasses"
    :disabled="loadingState"
    @click="handleClick"
  >
    <span class="spinner spinner-border" v-if="loadingState"></span>
    <slot v-else />
  </button>
</template>
<script lang="ts">
export default {
  inheritAttrs: false
}
</script>
<script setup lang="ts">
import { computed, ref, useAttrs } from 'vue'

const attrs: any = useAttrs()
let isLoading = ref<boolean>(false)

const computedClasses = computed<object>(() => {
  let classes: any = { 'is-loading': loadingState }
  if (attrs.class) {
    classes[attrs.class] = true
  }
  return classes
})

const loadingState = computed<boolean>(() => {
  return isLoading.value
})

async function handleClick (event: any) {
  try {
    isLoading.value = true;
    await attrs.onClick(event);
  } catch (error) {
    throw error
  } finally {
    isLoading.value = false;
  }
}
</script>
