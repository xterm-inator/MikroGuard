<template>
  <div class="card card-md">
    <div class="card-body">
      <h2 class="card-title text-center mb-4">Login to your account</h2>
      <div v-if="tooManyAttemptsError" class="mb-3 alert alert-danger">{{ tooManyAttemptsError }}.</div>
      <div class="text-center d-grid mt-3" v-if="app.config.auth_type == AuthType.Google">
        <login-with-social @authenticated="handleCompletedLogin"></login-with-social>
      </div>
      <form v-else class="d-grid mt-3">
        <div class="mb-3">
          <label class="form-label required">Email</label>
          <input type="text" class="form-control" name="email" v-model="email" placeholder="User Email" :class="{ 'is-invalid': errors.email }">
          <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
        </div>
        <div class="mb-4">
          <label class="form-label required">Password</label>
          <input type="password" class="form-control" name="password" v-model="password" placeholder="password" :class="{ 'is-invalid': errors.password }">
          <div class="invalid-feedback" v-if="errors.password">{{ errors.password }}</div>
        </div>
        <async-button type="submit" class="btn btn-primary w-100" @click="handleLogin">Sign in</async-button>
      </form>
    </div>
  </div>
</template>
<script setup lang="ts">
import LoginWithSocial from '@/components/LoginWithSocial.vue'
import { useRouter } from 'vue-router'
import { AuthType, useAppStore } from '@/stores/app'
import { toFormValidator } from '@vee-validate/zod'
import * as zod from 'zod'
import { useForm, useField } from 'vee-validate'
import AsyncButton from '@/components/AsyncButton.vue'
import { ref } from 'vue'

const tooManyAttemptsError = ref(null)

const router = useRouter()
const app = useAppStore()

const store = useAppStore()

const validationSchema = toFormValidator(
    zod.object({
      email: zod.string().min(1).email(),
      password: zod.string(),
    })
)

const { handleSubmit, errors, values } = useForm({
  validationSchema,
});

const { value: email, resetField: resetEmail } = useField<string>('email')
const { value: password, resetField: resetPassword } = useField<string|null>('password')

resetEmail({
  value: store.user.email
})

resetPassword({
  value: store.user.password
})

const handleLogin = handleSubmit(async (values, actions) => {

  try {
    await store.login(values.email, values.password)

    await store.authorize()

    handleCompletedLogin()
  } catch (e: any) {
    if (e.response.status === 422) {
      actions.setErrors(e.response.data.errors)
    } else if (e.response.status === 429) {
      tooManyAttemptsError.value = e.response.data.errors.email[0]

      return
    }

    throw e
  }
})

const handleCompletedLogin = () => {
  router.push({ name: 'connection' })
}
</script>
