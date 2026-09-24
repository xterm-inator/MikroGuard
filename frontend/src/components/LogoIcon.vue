<template>
  <img
    :src="logoSrc"
    :width="size"
    :height="size"
    class="mikroguard-logo-icon"
    alt="MikroGuard Logo"
  />
</template>

<script setup lang="ts">
import logoSrc from '@/assets/logo-icon.svg'

withDefaults(
  defineProps<{
    size?: number | string
  }>(),
  {
    size: 28
  }
)
</script>
