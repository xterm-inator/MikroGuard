<template>
  <div class="container-xl">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          Users
        </h3>
        <div class="card-options">
          <button class="btn btn-outline-secondary btn-sm ml-2" @click="handleAddUser">Add User</button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="dimmer">
          <div class="dimmer-content list-region">
            <table class="table table-hover table-outline table-vcenter table-mobile-md card-table m-0">
              <thead>
                <tr>
                  <th>Email</th>
                  <th>Activity</th>
                  <th>Handshake</th>
                  <th width="50"></th>
                </tr>
              </thead>
              <tbody>
                <user-list-item v-for="user in users" :key="user.id" :user="user"></user-list-item>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <add-user-modal ref="addUser"></add-user-modal>
</template>

<script setup lang="ts">
import UserListItem from '@/views/UserListItem.vue'
import AddUserModal from '@/components/AddUserModal.vue'
import { useUserStore } from '@/stores/user'
import { computed, onMounted, ref } from 'vue'

const userStore = useUserStore();

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
