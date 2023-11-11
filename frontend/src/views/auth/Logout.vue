<template>
</template>
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app'
import { onMounted } from 'vue'

const router = useRouter()

const store = useAppStore()

onMounted(async () => {
  await store.logout()
  router.push({ name: 'login' })
})
</script>
