<template>
  <div class="container-xl d-flex flex-column justify-content-center" v-if="!loading">
    <empty v-if="!config?.length" :id="id" @add="handleAddConnection"></empty>
    <template v-else>
      <async-button class="btn btn-primary col-2 mb-10" @click.prevent="handleAddConnection">
          <plus-icon></plus-icon>
          Create WireGuard config
      </async-button>
      <connection-details :id="id"></connection-details>
    </template>

    <add-connection-modal :id="id" ref="addConnection"></add-connection-modal>
  </div>
</template>
<script setup lang="ts">
import Empty from '@/components/connections/Empty.vue'
import ConnectionDetails from '@/components/connections/Details.vue'
import { useConfigStore } from '@/stores/config'
import { computed, onMounted, onBeforeUnmount, ref } from 'vue'
import AddConnectionModal from '@/components/connections/AddConnectionModal.vue'

interface Props {
  id: string
}

const props = defineProps<Props>()
let loading = ref<boolean>(true)
const addConnection = ref<typeof AddConnectionModal>()
const configStore = useConfigStore()

const config = computed(() => {
  return configStore.config
})

let interval: NodeJS.Timeout

onMounted(async () => {
  configStore.resetConfig()
  try {
    await configStore.getConfig(props.id)
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false

    interval = setInterval(() => {
      configStore.getConfig(props.id)
    }, 10000)
  }
})

onBeforeUnmount(() => {
  if (interval) {
    clearInterval(interval)
  }
})

const handleAddConnection = async () => {
  if (addConnection && addConnection.value) {
    addConnection.value.modal.open()
  }
}
</script>
