import config from '../config.js'

function buildApiUrl(endpoint) {
  const baseUrl = config.apiDomain.replace(/\/$/, '')
  const path = endpoint.replace(/^\//, '')
  return `${baseUrl}/${path}`
}

function getAuthHeaders() {
  const token = localStorage.getItem('skProductionToken')

  if (!token) {
    return {}
  }

  return {
    Authorization: `Bearer ${token}`,
  }
}

export async function readJsonResponse(response) {
  const data = await response.json().catch(() => ({}))

  if (!response.ok) {
    throw new Error(data.error || 'Something went wrong. Please try again.')
  }

  return data
}

export async function get(endpoint, options = {}) {
  const url = buildApiUrl(endpoint)
  return fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    ...options,
  })
}

export async function post(endpoint, data, options = {}) {
  const url = buildApiUrl(endpoint)
  return fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    body: JSON.stringify(data),
    ...options,
  })
}

export async function put(endpoint, data, options = {}) {
  const url = buildApiUrl(endpoint)
  return fetch(url, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    body: JSON.stringify(data),
    ...options,
  })
}

export async function del(endpoint, options = {}) {
  const url = buildApiUrl(endpoint)
  return fetch(url, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    ...options,
  })
}

export function getApiUrl(endpoint) {
  return buildApiUrl(endpoint)
}
