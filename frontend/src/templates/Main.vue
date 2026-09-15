<template>
  <div class="page min-vh-100 d-flex flex-column">
    <header class="navbar navbar-expand-md sticky-top d-print-none">
      <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
          <router-link :to="{ name: 'connections' }" class="text-decoration-none d-flex align-items-center text-reset">
            <span class="avatar avatar-sm bg-primary-lt rounded me-2">
              <shield-lock-icon class="text-primary" :size="20"/>
            </span>
            <span class="fw-bold fs-3">MikroGuard</span>
          </router-link>
        </div>

        <div class="navbar-nav flex-row order-md-last align-items-center gap-2">
          <button class="btn btn-icon btn-ghost-secondary rounded-circle" type="button" @click="toggleTheme" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'" aria-label="Toggle theme">
            <sun-icon v-if="isDark" :size="18" />
            <moon-icon v-else :size="18" />
          </button>

          <div class="nav-item dropdown ms-2">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
              <span class="avatar avatar-sm bg-primary-lt rounded-circle">
                <user-icon :size="18"></user-icon>
              </span>
              <div class="d-none d-xl-block ps-2">
                <div class="fw-medium">{{ user.username }}</div>
                <div class="mt-1 small text-muted text-capitalize">{{ user.role }}</div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-sm">
              <div class="dropdown-header">Signed in as <strong>{{ user.username }}</strong></div>
              <div class="dropdown-divider"></div>
              <router-link class="dropdown-item text-danger" :to="{ name: 'logout' }">
                <logout-icon class="me-2" :size="16" />
                Logout
              </router-link>
            </div>
          </div>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu">
          <div class="d-flex flex-column flex-md-row flex-fill align-items-md-center">
            <ul class="navbar-nav">
              <nav-item name="connections">
                <template v-slot:icon>
                  <network-icon :size="18"/>
                </template>
                <template v-slot:text>
                  Connections
                </template>
              </nav-item>
              <nav-item name="users" v-if="user.role === 'admin'">
                <template v-slot:icon>
                  <users-icon :size="18"/>
                </template>
                <template v-slot:text>
                  Users
                </template>
              </nav-item>
            </ul>
          </div>
        </div>
      </div>
    </header>

    <div class="page-wrapper">
      <div class="page-body py-4">
        <router-view/>
      </div>
      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              <ul class="list-inline list-inline-dots mb-0 text-muted">
                <li class="list-inline-item">
                  Powered by
                  <a href="https://github.com/xterm-inator/MikroGuard" target="_blank" rel="noopener noreferrer" class="link-secondary">
                    MikroGuard
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>
<script setup lang="ts">
import { RouterView } from 'vue-router'
import { UsersIcon, NetworkIcon, UserIcon, ShieldLockIcon, MoonIcon, SunIcon, LogoutIcon, BrandGithubIcon } from 'vue-tabler-icons'
import NavItem from '@/components/NavItem.vue'
import { useAppStore } from '@/stores/app'
import { computed, ref, onMounted } from 'vue'

const store = useAppStore()

const user = computed(() => {
  return store.user
})

const isDark = ref(false)

function applyTheme(dark: boolean) {
  isDark.value = dark
  const theme = dark ? 'dark' : 'light'
  document.body.setAttribute('data-bs-theme', theme)
  localStorage.setItem('tabler-theme', theme)
}

function toggleTheme() {
  applyTheme(!isDark.value)
}

onMounted(() => {
  const saved = localStorage.getItem('tabler-theme')
  if (saved === 'dark' || (!saved && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    applyTheme(true)
  } else {
    applyTheme(false)
  }
})
</script>
