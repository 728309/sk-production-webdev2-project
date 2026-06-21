import { get, post, readJsonResponse } from '../utils/api.js'

export async function login(email, password) {
  const response = await post('/auth/login', { email, password })
  return readJsonResponse(response)
}

export async function register(name, email, password) {
  const response = await post('/auth/register', { name, email, password })
  return readJsonResponse(response)
}

export async function fetchCurrentUser() {
  const response = await get('/auth/me')
  return readJsonResponse(response)
}
