<template>
  <Main v-if="route.meta.template === 'main'"></Main>
  <auth v-else-if="route.meta.template === 'auth'"></auth>
</template>

<style lang="sass">
@import '@/assets/scss/app.scss'
@import '@vueform/multiselect/themes/default.scss'
</style>

<script setup lang="ts">
import Main from '@/templates/Main.vue'
import Auth from '@/templates/Auth.vue'
import { useRoute } from 'vue-router'

const route = useRoute()
</script>
