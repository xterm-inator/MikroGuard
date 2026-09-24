<template>
  <div class="page-header d-print-none mb-4">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle" v-if="pretitle">{{ pretitle }}</div>
          <h2 class="page-title">{{ title }}</h2>
          <div class="text-muted mt-1 small" v-if="subtitle">{{ subtitle }}</div>
        </div>
        <div class="col-auto ms-auto d-print-none" v-if="$slots.actions">
          <div class="btn-list">
            <slot name="actions"></slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string
  pretitle?: string
  subtitle?: string
}>()
</script>
