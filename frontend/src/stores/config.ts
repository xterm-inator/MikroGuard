import { defineStore } from 'pinia'
import { http } from '@/utils'

export interface Config {
  peer_name: string
  peer_private_key: string
  peer_public_key: string
  peer_preshared_key: string
  server_name: string
  server_public_key: string
  endpoint: string
  dns: string
  allowed_ips: string
  address: string
  rx: number
  tx: number
  last_handshake: string|null
  last_connected_from: string
}


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
