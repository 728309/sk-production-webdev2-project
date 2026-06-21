import { defineStore } from 'pinia'
import { get, post } from '../utils/api.js'

const TOKEN_KEY = 'skProductionToken'

async function readJsonResponse(response) {
  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.error || 'Something went wrong. Please try again.')
  }

  return data
}

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
      const response = await post('/auth/login', { email, password })
      const data = await readJsonResponse(response)

      this.token = data.token
      this.user = data.user
      localStorage.setItem(TOKEN_KEY, data.token)

      return data
    },

    async register(name, email, password) {
      const response = await post('/auth/register', { name, email, password })
      return readJsonResponse(response)
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
        const response = await get('/auth/me')
        const user = await readJsonResponse(response)
        this.user = user

        return user
      } catch (error) {
        this.logout()
        return null
      }
    },
  },
})
