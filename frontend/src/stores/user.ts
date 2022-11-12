import { defineStore } from 'pinia'
import { http } from '@/utils'
import { clone } from 'lodash'
import { DefaultUser } from '@/models/user.model'
import type { User } from '@/models/user.model'
import type { AxiosResponse } from "axios";

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
