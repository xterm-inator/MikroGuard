import { defineStore } from 'pinia'
import { http } from '@/utils'
import type { AxiosResponse } from 'axios'

interface Role {
  value: string,
  label: string
}

export const useRoleStore = defineStore({
  id: 'role',
  state: () => ({
    roles: <Array<Role>>[]
  }),

  getters: {
  },

  actions: {
    async getRoles(): Promise<AxiosResponse> {
      const response = await http.get('lists/roles')
      this.roles = response.data.data
      return response
    }
  }
})
