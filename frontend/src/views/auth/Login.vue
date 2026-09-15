<template>
  <div class="card card-md shadow-sm border-0">
    <div class="card-body">
      <div class="text-center mb-4">
        <span class="avatar avatar-lg bg-primary-lt rounded-circle mb-3">
          <shield-lock-icon class="text-primary" :size="32"/>
        </span>
        <h2 class="card-title fs-2 mb-1">Welcome to MikroGuard</h2>
        <div class="text-muted">Sign in to manage WireGuard connections</div>
      </div>
      <div v-if="tooManyAttemptsError" class="mb-3 alert alert-danger">{{ tooManyAttemptsError }}.</div>
      <div class="text-center d-grid mt-3" v-if="app.config.auth_type == AuthType.Google">
        <login-with-social @authenticated="handleCompletedLogin"></login-with-social>
      </div>
      <form v-else class="d-grid mt-3" @submit.prevent="handleLogin">
        <div class="mb-3">
          <label class="form-label required">Username or Email</label>
          <div class="input-icon">
            <span class="input-icon-addon">
              <user-icon :size="18"/>
            </span>
            <input type="text" class="form-control" name="username" v-model="username" placeholder="Username" :class="{ 'is-invalid': errors.username }">
          </div>
          <div class="invalid-feedback d-block" v-if="errors.username">{{ errors.username }}</div>
        </div>
        <div class="mb-4">
          <label class="form-label required">Password</label>
          <div class="input-icon">
            <span class="input-icon-addon">
              <lock-icon :size="18"/>
            </span>
            <input type="password" class="form-control" name="password" v-model="password" placeholder="Password" :class="{ 'is-invalid': errors.password }">
          </div>
          <div class="invalid-feedback d-block" v-if="errors.password">{{ errors.password }}</div>
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
import { ShieldLockIcon, UserIcon, LockIcon } from 'vue-tabler-icons'
import { ref } from 'vue'

const tooManyAttemptsError = ref(null)

const router = useRouter()
const app = useAppStore()

const store = useAppStore()

const validationSchema = toFormValidator(
    zod.object({
      username: zod.string().min(1),
      password: zod.string(),
    })
)

const { handleSubmit, errors, values } = useForm({
  validationSchema,
});

const { value: username, resetField: resetUsername } = useField<string>('username')
const { value: password, resetField: resetPassword } = useField<string|null>('password')

resetUsername({
  value: store.user.username
})

resetPassword({
  value: store.user.password
})

const handleLogin = handleSubmit(async (values, actions) => {

  try {
    await store.login(values.username, values.password)

    await store.authorize()

    handleCompletedLogin()
  } catch (e: any) {
    if (!e.response) {
      throw e
    }

    if (e.response.status === 422) {
      actions.setErrors(e.response.data.errors)
    } else if (e.response.status === 429) {
      tooManyAttemptsError.value = e.response.data.errors.username[0]

      return
    }

    throw e
  }
})

const handleCompletedLogin = () => {
  router.push({ name: 'connections' })
}
</script>
