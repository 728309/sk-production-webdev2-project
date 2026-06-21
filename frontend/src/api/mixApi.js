import { del, get, post, put, readJsonResponse } from '../utils/api.js'

function withQuery(endpoint, params) {
  const query = params instanceof URLSearchParams ? params.toString() : ''
  return query ? `${endpoint}?${query}` : endpoint
}

export async function fetchMixes(params = new URLSearchParams()) {
  const response = await get(withQuery('/mixes', params))
  return readJsonResponse(response)
}

export async function fetchMixById(id) {
  const response = await get(`/mixes/${id}`)
  return readJsonResponse(response)
}

export async function fetchFeaturedMixes() {
  const response = await get('/mixes/featured')
  return readJsonResponse(response)
}

export async function submitMix(mixData) {
  const response = await post('/mixes', mixData)
  return readJsonResponse(response)
}

export async function fetchMyMixes() {
  const response = await get('/my/mixes')
  return readJsonResponse(response)
}

export async function fetchPendingMixes() {
  const response = await get('/admin/mixes/pending')
  return readJsonResponse(response)
}

export async function fetchAdminMixes(params = new URLSearchParams()) {
  const response = await get(withQuery('/admin/mixes', params))
  return readJsonResponse(response)
}

export async function approveMix(id) {
  const response = await put(`/admin/mixes/${id}/approve`, {})
  return readJsonResponse(response)
}

export async function rejectMix(id, reviewNote) {
  const response = await put(`/admin/mixes/${id}/reject`, { reviewNote })
  return readJsonResponse(response)
}

export async function updateAdminMix(id, mixData) {
  const response = await put(`/admin/mixes/${id}`, mixData)
  return readJsonResponse(response)
}

export async function deleteAdminMix(id) {
  const response = await del(`/admin/mixes/${id}`)
  return readJsonResponse(response)
}

export async function setMixFeatured(id, featured) {
  const endpoint = featured ? 'feature' : 'unfeature'
  const response = await put(`/admin/mixes/${id}/${endpoint}`, {})
  return readJsonResponse(response)
}
