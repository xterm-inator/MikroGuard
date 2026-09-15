<template>
  <tr>
    <td>
      <div class="d-flex align-items-center">
        <span class="avatar avatar-sm bg-primary-lt rounded-circle me-3 fw-bold">
          {{ props.user.username.slice(0, 2).toUpperCase() }}
        </span>
        <div>
          <router-link class="fw-medium text-reset text-decoration-none" :to="{ name: 'users.connection', params: { id: props.user.id } }">
            {{ props.user.username }}
          </router-link>
          <div class="small text-muted text-capitalize">{{ props.user.role || 'WireGuard User' }}</div>
        </div>
      </div>
    </td>
    <td>
      <div class="d-flex align-items-center text-nowrap gap-2">
        <span class="badge bg-green-lt d-inline-flex align-items-center">
          <arrow-down-icon :size="14" class="me-1"></arrow-down-icon>
          {{ prettyBytes(props.user.rx ?? 0) }}
        </span>
        <span class="badge bg-blue-lt d-inline-flex align-items-center">
          <arrow-up-icon :size="14" class="me-1"></arrow-up-icon>
          {{ prettyBytes(props.user.tx ?? 0) }}
        </span>
      </div>
    </td>
    <td>
      <span class="badge" :class="props.user.last_handshake ? 'bg-success-lt' : 'bg-secondary-lt'">
        {{ lastHandshake }}
      </span>
    </td>
    <td class="text-end">
      <button class="btn btn-icon btn-ghost-danger btn-sm" title="Delete User" @click="handleDelete">
        <trash-icon :size="16" />
      </button>
    </td>
  </tr>
</template>
<script setup lang="ts">
import { useUserStore } from '@/stores/user'
import type { User } from '@/stores/user'
import swal from 'sweetalert'
import prettyBytes from 'pretty-bytes'
import { computed } from 'vue'
import dayjs from 'dayjs'
import { ArrowDownIcon, ArrowUpIcon, TrashIcon } from 'vue-tabler-icons'

interface Props {
  user: User
}

const props = defineProps<Props>()

const userStore = useUserStore();

const lastHandshake = computed(() => {
  if (props.user.last_handshake) {
    return dayjs.utc(props.user.last_handshake).local().fromNow()
  }

  return '-'
})

async function handleDelete(): Promise<void> {
  const response = await swal({
    title: 'Are you sure?',
    text: 'This will remove all WireGuard settings from the router for this user.',
    icon: 'warning',
    buttons: {
      cancel: true,
      ok: {
        text: 'Yes',
        className: 'swal-button--danger',
        closeModal: false
      }
    },
    dangerMode: true
  })
  if (response && props.user.id) {
    await userStore.deleteUser(props.user.id)
    if (swal.stopLoading && swal.close) {
      swal.stopLoading()
      swal.close()
    }
  }
}
</script>
