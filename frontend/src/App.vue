<template>
  <template v-if="appStore.config">
    <Main v-if="route.meta.template === 'main'"></Main>
    <Auth v-else-if="route.meta.template === 'auth'"></Auth>
  </template>
</template>

<style lang="sass">
@import '@/assets/scss/app.scss'
@import '@vueform/multiselect/themes/default.scss'
</style>

<script setup lang="ts">
import Main from '@/templates/Main.vue'
import Auth from '@/templates/Auth.vue'
import { useRoute } from 'vue-router'
import { onMounted } from 'vue'
import { useAppStore } from '@/stores/app'

const route = useRoute()
const appStore = useAppStore()

onMounted(() => {
  appStore.getAppConfig()
})
</script>
