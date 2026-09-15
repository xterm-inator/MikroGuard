<template>
  <div class="card mb-3" v-for="detail in config" :key="detail.id">
    <div class="card-body">
      <connection :config="detail" :onDelete="props.onDelete"/>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useConfigStore } from '@/stores/config'
import { computed } from 'vue'
import Connection from '@/components/connections/Connection.vue'

interface Props {
  onDelete: (id: string) => Promise<void>
}

const props = defineProps<Props>()

const configStore = useConfigStore()

const config = computed(() => configStore.config)

</script>
