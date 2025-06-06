<template>
  <div class="empty">
    <div class="empty-img"><img :src="image" height="128" alt="">
    </div>
    <p class="empty-title">No configuration found</p>
    <p class="empty-subtitle text-muted">
      Click below to create your WireGuard Configuration
    </p>
    <div class="empty-action">
      <async-button class="btn btn-primary" @click.prevent="handleCreateConfig">
        <plus-icon></plus-icon>
        Create your WireGuard config
      </async-button>
    </div>
  </div>
</template>
<script setup lang="ts">
import { PlusIcon } from 'vue-tabler-icons'
import image from '@/assets/illustrations/undraw_quitting_time.svg'
import { useConfigStore } from '@/stores/config'

interface Props {
  id: string
}

const props = defineProps<Props>()

const store = useConfigStore()

function handleCreateConfig() {
  return store.createConfig(props.id)
}

</script>
