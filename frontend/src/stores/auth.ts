import { defineStore } from 'pinia'
import { http } from '@/utils'
import type { User } from '@/stores/user'
import { DefaultUser } from '@/stores/user'
import { clone } from 'lodash'

export const useAuthStore = defineStore({
  id: 'auth',
  state: () => ({
    user: <User>clone(DefaultUser),
  }),

  getters: {
  },

  actions: {
    async fetchOauthUrl (provider: string): Promise<string> {
      const response = await http.get(`auth/oauth/${provider}`)
      return response.data.url
    },

    async authorize(): Promise<void> {
      const response = await http.get('me')
      this.user = response.data.data
    },

    async logout (): Promise<void> {
      await http.delete('auth').catch()
      this.resetUser()
    },

    resetUser (): void {
      this.user = clone(DefaultUser)
    }
  }
})
