<template>
</template>
<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { onMounted } from 'vue'

const router = useRouter()

const store = useAuthStore()

onMounted(async () => {
  await store.logout()
  router.push({ name: 'login' })
})
</script>
