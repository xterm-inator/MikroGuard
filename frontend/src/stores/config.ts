import { defineStore } from 'pinia'
import { http } from '@/utils'
import type { Config } from '@/models/config.model'

export const useConfigStore = defineStore({
  id: 'config',
  state: () => ({
    config: <Config|null>null,
  }),

  getters: {
  },

  actions: {
    async getConfig(userId: string): Promise<any> {
      const response = await http.get(`config/${userId}`)

      this.config = response.data.data

      return response
    },

    async createConfig(userId: string): Promise<any> {
      const response = await http.post(`config/${userId}`)

      this.config = response.data.data

      return response
    },

    async deleteConfig(userId: string): Promise<any> {
      await http.delete(`config/${userId}`)

      this.resetConfig()
    },

    resetConfig (): void {
      this.config = null
    }
  }
})
