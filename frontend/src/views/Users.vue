<template>
  <div>
    <page-header title="Users" pretitle="Administration" subtitle="Manage registered VPN users">
      <template #actions>
        <button class="btn btn-primary d-inline-flex align-items-center" @click="handleAddUser">
          <plus-icon class="me-1" :size="18"/>
          Add User
        </button>
      </template>
    </page-header>

    <div class="container-xl">
      <div class="card shadow-sm border-0">
        <div class="table-responsive">
          <table class="table table-hover table-vcenter card-table m-0">
            <thead>
              <tr>
                <th>User</th>
                <th>Traffic (Rx / Tx)</th>
                <th>Last Handshake</th>
                <th class="w-1 text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <user-list-item v-for="user in users" :key="user.id" :user="user"></user-list-item>
              <tr v-if="!users?.length">
                <td colspan="4" class="text-center text-muted py-4">No users found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <add-user-modal ref="addUser"></add-user-modal>
  </div>
</template>

<script setup lang="ts">
import PageHeader from '@/components/PageHeader.vue'
import UserListItem from '@/views/UserListItem.vue'
import AddUserModal from '@/components/AddUserModal.vue'
import { useUserStore } from '@/stores/user'
import { computed, onMounted, ref } from 'vue'
import { PlusIcon } from 'vue-tabler-icons'

const userStore = useUserStore()

const addUser = ref<typeof AddUserModal>()

const users = computed(() => {
  return userStore.users
})

onMounted(() => {
  userStore.getUsers()
})

const handleAddUser = () => {
  if (addUser && addUser.value) {
    addUser.value.modal.open()
  }
}
</script>
