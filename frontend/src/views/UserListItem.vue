<template>
  <tr>
    <td>
      <router-link :to="{ name: 'users.connection', params: { id: props.user.id } }">{{ props.user.email }}</router-link>
    </td>
    <td>
      {{ prettyBytes(props.user.rx ?? 0) }}
      <arrow-down-icon class="me-4"></arrow-down-icon>
      {{ prettyBytes(props.user.tx ?? 0) }}
      <arrow-up-icon class="me-4"></arrow-up-icon>
    </td>
    <td>
      {{ lastHandshake }}
    </td>
    <td class="text-right">
      <button class="btn btn-danger btn-sm pull-right" @click="handleDelete">Delete</button>
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
import { ArrowDownIcon, ArrowUpIcon } from 'vue-tabler-icons'

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
