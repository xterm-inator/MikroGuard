import { defineStore } from 'pinia'
import { http } from '@/utils'
import { clone } from 'lodash'
import type { AxiosResponse } from 'axios'

export interface User {
  id?: string
  role: string
  email: string
  data_up?: number
  data_down?: number
  last_handshake?: Date,
  rx?: number,
  tx?: number,
  password?: string|null,
  password_confirmation?: string|null,
}

export const DefaultUser: User = {
  email: '',
  role: 'user',
  password: null,
  password_confirmation: null,
}

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    user: clone(DefaultUser),
    users: <Array<User>>[]
  }),

  getters: {
  },

  actions: {
    async getUsers(): Promise<AxiosResponse> {
      const response = await http.get('users')
      this.users = response.data.data
      return response
    },

    async storeUser(): Promise<any> {
      const response = await http.post('users', { ...this.user })
      this.users.unshift(response.data.data)
    },

    async deleteUser(id: string): Promise<any> {
      await http.delete(`users/${id}`)
      const index = this.users.findIndex((item) => item.id === id)
      this.users.splice(index, 1)
    },

    resetUser (): void {
      this.user = clone(DefaultUser)
    }
  }
})
