<template>
  <div class="page">
    <header class="navbar navbar-expand-md navbar-light d-print-none">
      <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
          MicroGuard
        </a>

        <div class="navbar-nav flex-row order-md-last">
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
               aria-label="Open user menu">
              <span class="avatar avatar-sm"><user-icon></user-icon></span>
              <div class="d-none d-xl-block ps-2">
                <div>{{ user.email }}</div>
                <div class="mt-1 small text-muted text-capitalize">{{ user.role }}</div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <router-link class="dropdown-item" :to="{ name: 'logout' }">Logout</router-link>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="navbar-expand-md">
      <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar navbar-light">
          <div class="container-xl">
            <ul class="navbar-nav">
              <nav-item name="connection">
                <template v-slot:icon>
                  <network-icon/>
                </template>
                <template v-slot:text>
                  Connection
                </template>
              </nav-item>
              <nav-item name="users" v-if="user.role === 'admin'">
                <template v-slot:icon>
                  <users-icon/>
                </template>
                <template v-slot:text>
                  Users
                </template>
              </nav-item>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="page-wrapper">
      <div class="page-body">
        <router-view/>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { RouterView } from 'vue-router'
import { UsersIcon, NetworkIcon, UserIcon } from 'vue-tabler-icons'
import NavItem from '@/components/NavItem.vue'
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'

const store = useAuthStore()

const user = computed(() => {
  return store.user
})
</script>
