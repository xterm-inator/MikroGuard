<template>
  <div class="card card-md">
    <div class="card-body">
      <h2 class="card-title text-center mb-4">Login to your account</h2>
      <div class="text-center d-grid mt-3">
        <login-with-social @authenticated="handleLogin"></login-with-social>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import LoginWithSocial from '@/components/LoginWithSocial.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogin = () => {
  router.push({ name: 'connection' })
}
</script>
