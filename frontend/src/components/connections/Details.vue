<template>
  <div class="card mb-3" v-for="detail in config">
    <div class="card-body">
      <connection :config="detail" />
    </div>
  </div>
</template>
<script setup lang="ts">
import { useConfigStore } from '@/stores/config'
import { computed } from 'vue'
import Connection from '@/components/connections/Connection.vue'

const configStore = useConfigStore()

const config = computed(() => configStore.config)

</script>
