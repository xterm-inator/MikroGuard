<template>
  <div class="empty py-5">
    <div class="empty-img mb-4">
      <img :src="image" height="140" alt="No configurations" style="height: 140px; max-width: 100%;">
    </div>
    <p class="empty-title fs-2 fw-bold">No configuration found</p>
    <p class="empty-subtitle text-muted">
      Click below to create your WireGuard configuration
    </p>
    <div class="empty-action mt-3">
      <async-button class="btn btn-primary d-inline-flex align-items-center" @click.prevent="handleCreateConfig">
        <plus-icon class="me-1" :size="18"/>
        Create your WireGuard config
      </async-button>
    </div>
  </div>
</template>
<script setup lang="ts">
import { PlusIcon } from 'vue-tabler-icons'
import image from '@/assets/illustrations/undraw_quitting_time.svg'
import AsyncButton from '@/components/AsyncButton.vue'

const emit = defineEmits<{
  (e: 'add'): void
}>()

function handleCreateConfig() {
  emit('add')
}
</script>
