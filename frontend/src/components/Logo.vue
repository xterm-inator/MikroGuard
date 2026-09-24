<template>
  <div class="d-inline-flex align-items-center gap-2 logo-brand">
    <logo-icon :size="iconSize" />
    <div v-if="showText" class="d-flex flex-column lh-1">
      <div class="fw-bold" :class="textClass" style="letter-spacing: -0.02em;">
        Mikro<span class="text-primary">Guard</span>
      </div>
      <div v-if="subtitle" class="text-muted small text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.08em;">
        WireGuard for MikroTik
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import LogoIcon from '@/components/LogoIcon.vue'

const props = withDefaults(
  defineProps<{
    size?: number | string
    showText?: boolean
    subtitle?: boolean
    textClass?: string
  }>(),
  {
    size: 28,
    showText: true,
    subtitle: false,
    textClass: 'fs-3'
  }
)

const iconSize = computed(() => props.size)
</script>
