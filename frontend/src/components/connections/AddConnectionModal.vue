<template>
  <modal title="Add Connection" ref="modal" @open="handleOpen">
    <form>
      <div class="mb-3">
        <label class="form-label required">Connection Name</label>
        <input type="text" class="form-control" name="name" v-model="name" placeholder="Default" :class="{ 'is-invalid': errors.name }">
        <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
      </div>
    </form>
    <template #footer="{ close }">
      <button class="btn btn-link link-secondary" @click="close">Cancel</button>
      <async-button class="btn btn-primary ms-auto" @click="handleCreate"><plus-icon/> Create Connection</async-button>
    </template>
  </modal>
</template>
<script setup lang="ts">
import Modal from '@/components/Modal.vue'
import { ref } from 'vue'
import { PlusIcon } from 'vue-tabler-icons'
import { useField, useForm } from 'vee-validate'
import { toFormValidator } from '@vee-validate/zod'
import * as zod from 'zod'
import { useConfigStore } from '@/stores/config'

interface Props {
  id: string
}

const props = defineProps<Props>()

const modal = ref<typeof Modal>()
const store = useConfigStore()

const validationSchema = toFormValidator(
    zod.object({
      name: zod.string().min(1).max(255)
    })
)

const { handleSubmit, errors, values, handleReset } = useForm({
  validationSchema,
});

const { value: name, resetField: resetName } = useField<string>('name')

resetName({
  value: 'Default'
})

const handleCreate = handleSubmit(async (values, actions) => {
  try {
    await store.createConfig(props.id, values.name)
    if (modal && modal.value) {
      modal.value.close()
    }
  } catch (e: any) {
    if (e.response.status === 422) {
      actions.setErrors(e.response.data.errors)
    }
  }
})

function handleOpen() {
  // store.resetConfig()
  handleReset()
}

defineExpose({
  modal
})
</script>
