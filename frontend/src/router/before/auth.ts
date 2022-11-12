import { useAuthStore } from '@/stores/auth'
import type { RouteLocationNormalized } from "vue-router";

export default async (to: RouteLocationNormalized, from: RouteLocationNormalized, next: any) => {
  const authStore = useAuthStore()

  const requiresAuth = to.matched.some((route) => route.meta.requiresAuth === true)

  try {
    if (!requiresAuth) {
      return next()
    }

    if (authStore.user.id === null || authStore.user.id === undefined) {
      await authStore.authorize()
    }

    return next()
  } catch (e) {
    if (requiresAuth) {
      return next({
        name: 'login',
        replace: true,
        query: { redirect: to.fullPath }
      })
    }

    return next()
  }
}
