import { defineStore } from 'pinia'
import { http } from '@/utils'
import type { AxiosResponse } from 'axios'

export enum AuthType {
  Form = 'form',
  Google = 'Google'
}

export interface AppConfig {
  auth_type: AuthType
}

export const useAppStore = defineStore({
  id: 'app',
  state: () => ({
    config: <AppConfig> {
      auth_type: 'form'
    }
  }),

  getters: {
  },

  actions: {
    async getAppConfig(): Promise<AxiosResponse> {
      const response = await http.get('app/config')
      this.config = response.data
      return response
    }
  }
})
