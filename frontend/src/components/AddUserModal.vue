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
      <template v-if="app.config.auth_type === AuthType.Form">
        <div class="mb-3">
          <label class="form-label required">Password</label>
          <input type="password" class="form-control" name="password" v-model="password" placeholder="password" :class="{ 'is-invalid': errors.password }">
          <div class="invalid-feedback" v-if="errors.password">{{ errors.password }}</div>
        </div>
        <div class="mb-3">
          <label class="form-label required">Password Confirmation</label>
          <input type="password" class="form-control" name="password" v-model="passwordConfirmation" placeholder="password" :class="{ 'is-invalid': errors.password_confirmation }">
          <div class="invalid-feedback" v-if="errors.password_confirmation">{{ errors.password_confirmation }}</div>
        </div>
      </template>
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
import { AuthType, useAppStore } from '@/stores/app'

const modal = ref<typeof Modal>()
const store = useUserStore()
const app = useAppStore()

const validationSchema = toFormValidator(
    zod.object({
      email: zod.string().min(1).email(),
      role: zod.string(),
      password: zod.string().nullable(),
      password_confirmation: zod.string().nullable()
    })
)

const { handleSubmit, errors, values, handleReset } = useForm({
  validationSchema,
});

const { value: email, resetField: resetEmail } = useField<string>('email')
const { value: role, resetField: resetRole } = useField<string>('role')
const { value: password, resetField: resetPassword } = useField<string|null>('password')
const { value: passwordConfirmation, resetField: resetPasswordConfirmation } = useField<string|null>('password_confirmation')


resetRole({
  value: store.user.role
})

resetEmail({
  value: store.user.email
})

resetPassword({
  value: store.user.password
})

resetPasswordConfirmation({
  value: store.user.password_confirmation
})

const handleCreate = handleSubmit(async (values, actions) => {
  store.user.email = values.email
  store.user.role = values.role
  store.user.password = values.password
  store.user.password_confirmation = values.password_confirmation
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
