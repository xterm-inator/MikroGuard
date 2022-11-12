<template>
  <multiselect
    v-model="selectedRole"
    :options="roles"
  />
</template>
<script setup lang="ts">
import Multiselect from '@vueform/multiselect'
import { useRoleStore } from '@/stores/lists/roles'
import { computed, onMounted } from 'vue'

const store = useRoleStore()

const emit = defineEmits(['update:role'])

interface Props {
  role: string
}

const props = defineProps<Props>()

const roles = computed(() => {
  return store.roles
})

const selectedRole = computed({
  get () {
    return props.role
  },
  set (value) {
    emit('update:role', value)
  }
})

onMounted(() => {
  store.getRoles()
})
</script>
