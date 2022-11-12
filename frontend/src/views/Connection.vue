<template>
  <div class="container-xl d-flex flex-column justify-content-center" v-if="!loading">
    <empty v-if="!config" :id="id"></empty>
    <connection-details v-else :id="id"></connection-details>
  </div>
</template>
<script setup lang="ts">
import Empty from '@/components/connection/Empty.vue'
import ConnectionDetails from '@/components/connection/Details.vue'
import { useConfigStore } from '@/stores/config'
import { computed, onMounted, onBeforeUnmount, ref } from 'vue'

interface Props {
  id: string
}

const props = defineProps<Props>()
let loading = ref<boolean>(true)
const configStore = useConfigStore()

const config = computed(() => {
  return configStore.config
})

let interval: NodeJS.Timer

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
</script>
