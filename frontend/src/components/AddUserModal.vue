<template>
  <modal title="Add User" ref="modal" @open="handleOpen">
    <form>
      <div class="mb-3">
        <label class="form-label required">Email</label>
        <input type="text" class="form-control" name="email" v-model="email" placeholder="User Email" :class="{ 'is-invalid': errors.email }">
        <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
      </div>
      <div class="mb-3">
        <label class="form-label required">Role</label>
        <role-select v-model:role="role"></role-select>
        <div class="invalid-feedback" v-if="errors.role">{{ errors.role }}</div>
      </div>
    </form>
    <template #footer="{ close }">
      <button class="btn btn-link link-secondary" @click="close">Cancel</button>
      <async-button class="btn btn-primary ms-auto" @click="handleCreate"><plus-icon/> Create User</async-button>
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
import { useUserStore } from '@/stores/user'
import RoleSelect from '@/components/forms/RoleSelect.vue'

const modal = ref<typeof Modal>()
const store = useUserStore()

const validationSchema = toFormValidator(
    zod.object({
      email: zod.string().min(1).email(),
      role: zod.string()
    })
)

const { handleSubmit, errors, values, handleReset } = useForm({
  validationSchema,
});

const { value: email, resetField: resetEmail } = useField<string>('email')
const { value: role, resetField: resetRole } = useField<string>('role')

resetRole({
  value: store.user.role
})

resetEmail({
  value: store.user.email
})

const handleCreate = handleSubmit(async (values, actions) => {
  store.user.email = values.email
  store.user.role = values.role
  try {
    await store.storeUser()
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
  store.resetUser()
  handleReset()
}

defineExpose({
  modal
})
</script>
