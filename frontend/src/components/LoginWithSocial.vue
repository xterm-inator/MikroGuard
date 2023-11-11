<template>
  <button class="btn btn-outline-google-plus btn-block mt-2 fs-2" @click="handleLogin">
    <brand-google-icon stroke-width="2" /> google
  </button>
</template>
<script setup lang="ts">
import { BrandGoogleIcon } from 'vue-tabler-icons'
import { defineEmits, onBeforeUnmount, onMounted } from 'vue'
import { useAppStore } from '@/stores/app'

const emit = defineEmits(['authenticated'])
const store = useAppStore()

onMounted(() => {
  window.addEventListener('message', handleMessage, false)
})

onBeforeUnmount(() => {
  window.removeEventListener('message', handleMessage)
})

const handleLogin = async () => {
  const newWindow = openWindow('', 'login')
  if (newWindow) {
    newWindow.location.href = await store.fetchOauthUrl('google')
  }
}

const handleMessage = async (event: MessageEvent) => {
  if (event.origin !== (import.meta.env.VITE_BACKEND ?? document.location.origin)) {
    return
  }

  await store.authorize()

  emit('authenticated', event.data)
}

const openWindow = (url: string | URL, title: string, options = {url: <string|URL>'', title: '', left: 0, top: 0, width: 0, height: 0}) => {
  options = {...options, width: 600, height: 720,  url, title }
  const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenLeft
  const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenTop
  const width = window.innerWidth || document.documentElement.clientWidth || window.screen.width
  const height = window.innerHeight || document.documentElement.clientHeight || window.screen.height
  options.left = ((width / 2) - (options.width / 2)) + dualScreenLeft
  options.top = ((height / 2) - (options.height / 2)) + dualScreenTop
  const optionsStr = Object.keys(options).reduce((acc, key) => {
    // @ts-ignore
    acc.push(`${key}=${options[key]}`)
    return acc
  }, []).join(',')
  const newWindow = window.open(url, title, optionsStr)
  if(newWindow) {
    newWindow.focus()
  }

  return newWindow
}

</script>
