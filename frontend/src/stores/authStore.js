import { defineStore } from 'pinia'
import { fetchCurrentUser, login as loginApi, register as registerApi } from '../api/authApi.js'

const TOKEN_KEY = 'skProductionToken'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem(TOKEN_KEY) || '',
    user: null,
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token && state.user),
    isAdmin: (state) => state.user?.role === 'admin',
  },
  actions: {
    async login(email, password) {
      const data = await loginApi(email, password)

      this.token = data.token
      this.user = data.user
      localStorage.setItem(TOKEN_KEY, data.token)

      return data
    },

    async register(name, email, password) {
      return registerApi(name, email, password)
    },

    logout() {
      this.token = ''
      this.user = null
      localStorage.removeItem(TOKEN_KEY)
    },

    async fetchMe() {
      if (!this.token) {
        return null
      }

      try {
        const user = await fetchCurrentUser()
        this.user = user

        return user
      } catch (error) {
        this.logout()
        return null
      }
    },
  },
})
