<template>
  <div class="page page-center">
    <div class="container-tight py-4">
      <router-view></router-view>
      <div class="text-center text-muted mt-3">
        <a href="https://github.com/xterm-inator/MikroGuard" target="_blank" rel="noopener noreferrer" class="link-secondary d-inline-flex align-items-center text-decoration-none small">
          <brand-github-icon :size="15" class="me-1" /> Powered by MikroGuard
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { BrandGithubIcon } from 'vue-tabler-icons'
</script>
