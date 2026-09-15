<template>
  <button
    :type="type"
    :class="computedClasses"
    :disabled="loadingState"
    @click="handleClick"
  >
    <span class="spinner spinner-border" v-if="loadingState"></span>
    <slot v-else></slot>
  </button>
</template>
<script lang="ts">
export default {
  inheritAttrs: false
}
</script>
<script setup lang="ts">
import { computed, ref, useAttrs } from 'vue'

interface Props {
  type?: 'button' | 'submit' | 'reset'
}

withDefaults(defineProps<Props>(), {
  type: 'button'
})

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
