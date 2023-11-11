import { defineStore } from 'pinia'
import { http } from '@/utils'
import type { AxiosResponse } from 'axios'
import type { User } from '@/stores/user'
import { DefaultUser } from '@/stores/user'
import { clone } from 'lodash'

export enum AuthType {
  Form = 'form',
  Google = 'google'
}

export interface AppConfig {
  auth_type: AuthType
}

export const useAppStore = defineStore({
  id: 'app',
  state: () => ({
    config: <AppConfig> {
      auth_type: 'form'
    },
    user: <User>clone(DefaultUser),
    csrf: false,
  }),

  getters: {
  },

  actions: {
    async getAppConfig(): Promise<AxiosResponse> {
      const response = await http.get('app/config')
      this.config = response.data
      return response
    },
    async fetchOauthUrl (provider: string): Promise<string> {
      const response = await http.get(`auth/oauth/${provider}`)
      return response.data.url
    },

    login (email: string, password: string): Promise<void> {
      return http.post('auth', { email, password })
    },

    async authorize(): Promise<void> {
      const response = await http.get('me')
      this.user = response.data.data
    },

    async logout (): Promise<void> {
      await http.delete('auth').catch()
      this.resetUser()
      this.csrf = false
    },

    resetUser (): void {
      this.user = clone(DefaultUser)
    }
  }
})
