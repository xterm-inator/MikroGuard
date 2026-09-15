<template>
  <div>
    <page-header title="WireGuard Connections" pretitle="Overview" subtitle="Active client configurations">
      <template #actions v-if="config?.length">
        <async-button class="btn btn-primary" @click.prevent="handleAddConnection">
          <plus-icon class="me-1" :size="18"/>
          Create Connection
        </async-button>
      </template>
    </page-header>

    <div class="container-xl" v-if="!loading">
      <empty v-if="!config?.length" @add="handleAddConnection"></empty>
      <connection-details v-else :onDelete="handleDeleteConnection"></connection-details>
      <add-connection-modal :id="id" ref="addConnection"></add-connection-modal>
    </div>
  </div>
</template>
<script setup lang="ts">
import PageHeader from '@/components/PageHeader.vue'
import Empty from '@/components/connections/Empty.vue'
import ConnectionDetails from '@/components/connections/Details.vue'
import { useConfigStore } from '@/stores/config'
import { computed, onMounted, onBeforeUnmount, ref } from 'vue'
import AddConnectionModal from '@/components/connections/AddConnectionModal.vue'
import AsyncButton from '@/components/AsyncButton.vue'
import { PlusIcon } from 'vue-tabler-icons'

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

const handleDeleteConnection = async (id: string) => {
  await configStore.deleteConfig(props.id, id)
  await configStore.getConfig(props.id)
}
</script>
