import { createRouter, createWebHistory } from 'vue-router'
import registerBeforeHooks from './before'
import { useAppStore } from '@/stores/app'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      meta: { template: 'auth' },
      component: () => import('../views/auth/Login.vue')
    },
    {
      path: '/logout',
      name: 'logout',
      meta: { template: 'auth' },
      component: () => import('../views/auth/Logout.vue')
    },
    {
      path: '/',
      name: 'connection',
      meta: { template: 'main', requiresAuth: true },
      component: () => import('../views/Connection.vue'),
      props () {
        return {
          id: useAppStore().user.id
        }
      }
    },
    {
      path: '/users',
      name: 'users',
      meta: { template: 'main', requiresAuth: true, roles: ['admin'] },
      component: () => import('../views/Users.vue')
    },
    {
      path: '/users/:id',
      name: 'users.connection',
      meta: { template: 'main', requiresAuth: true, roles: ['admin'] },
      component: () => import('../views/Connection.vue'),
      props: true
    }
  ]
})

registerBeforeHooks(router);

export default router
