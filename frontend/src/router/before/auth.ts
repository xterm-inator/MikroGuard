import { useAppStore } from '@/stores/app'
import type { RouteLocationNormalized } from "vue-router";

export default async (to: RouteLocationNormalized, from: RouteLocationNormalized, next: any) => {
  const authStore = useAppStore()

  const requiresAuth = to.matched.some((route) => route.meta.requiresAuth === true)
  const roles: any = to.meta.roles ?? []

  try {
    if (!requiresAuth) {
      return next()
    }

    if (authStore.user.id === null || authStore.user.id === undefined) {
      await authStore.authorize()
    }

    if (roles.length > 0 && !roles.includes(authStore.user.role)) {
      throw new Error('Unauthorized')
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
