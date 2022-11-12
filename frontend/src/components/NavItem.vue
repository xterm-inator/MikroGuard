<template>
  <router-link custom :to="{ name: name }" v-slot="{ navigate, href, isActive }">
    <li class="nav-item" :class="{ active: isActive }" @click="navigate">
      <a class="nav-link" :href="href">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
          <slot name="icon"></slot>
        </span>
        <span class="nav-link-title">
          <slot name="text"></slot>
        </span>
      </a>
    </li>
  </router-link>
</template>
<script setup lang="ts">
import { RouterLink } from 'vue-router'

defineProps<{
  name: string
}>()
</script>
