<template>
  <div class="card card-sm shadow-sm border-0">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-auto" v-if="$slots.icon">
          <span class="avatar rounded" :class="props.badgeClass || 'bg-primary-lt'">
            <slot name="icon"></slot>
          </span>
        </div>
        <div class="col">
          <div class="text-uppercase text-muted subheader mb-1">
            {{ props.title }}
          </div>
          <div class="h3 mb-0 fw-bold">
            {{ props.value }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
interface Props {
  value: string
  title: string
  badgeClass?: string
}

const props = withDefaults(defineProps<Props>(), {
  badgeClass: 'bg-primary-lt'
})
</script>
