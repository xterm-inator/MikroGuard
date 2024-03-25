import type { AxiosResponse, InternalAxiosRequestConfig } from 'axios'
import axios, { Axios } from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { useAppStore } from '@/stores/app'

/**
 * Generate URL to API.
 *
 * @return string
 */
const apiUrl = (path?: string) => {
  const url = import.meta.env.VITE_BACKEND ?? document.location.origin

  if (path === undefined) {
    return `${url}/api`
  }

  return `${url}/api/${path}`
}

const http: Axios = axios.create({
  baseURL: apiUrl(),
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

http.interceptors.request.use(async (request: InternalAxiosRequestConfig) => {
  const appStore = useAppStore()

  if (request.data === undefined && ['post', 'put'].includes(request.method?.toLowerCase() ?? '')) {
    request.data = {}
  }

  // For POST, PUT, and DELETE requests make a CSRF preflight if we have not already
  // made one for this page load.
  if (
    ['post', 'put', 'delete'].includes(request.method?.toLowerCase() ?? '') && !appStore.csrf
  ) {
    await http.get('/sanctum/csrf-cookie')
    appStore.csrf = true
  }

  return request
})

http.interceptors.response.use((response: AxiosResponse) => {
  return response
}, async (error) => {
  if (error.response && error.response.status === 401) {
    const route = Object.assign({ meta: {} }, useRoute())

    if (
      error.response.config.authorizePreflight === true ||
      route.name === 'login'
    ) {
      return Promise.reject(error)
    }

    if (
      route.name !== 'login' &&
      route.name !== 'logout' &&
      route.name !== null &&
      route.meta.requiresAuth === true
    ) {
      // store.dispatch('auth/resetUser')
    }
    useRouter().replace({ name: 'login' }).catch(() => {})

    return Promise.reject(error)
  }

  // For 403s we'll throw a blanket error notification and redirect them back to the dashboard.
  if (error.response) {
    if (error.response.status === 403 && error.response.config.throwForbiddens !== true) {
      console.log('You do not have permission to do that.')

      await useRouter().replace({ path: '/' })
    } else if (error.response.status === 500) {
      console.log('We encountered a server error. We have been alerted and will investigate.')
    }
  }

  return Promise.reject(error)
})

export { http, apiUrl }
