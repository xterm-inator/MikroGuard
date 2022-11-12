<template>
  <div class="card card-sm">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col">
          <div class="font-weight-medium">
            {{ props.value }}
          </div>
          <div class="text-muted">
            {{ props.title }}
          </div>
        </div>
        <div class="col-auto">
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
interface Props {
  value: string,
  title: string
}

const props = defineProps<Props>()
</script>
