import { get, post, readJsonResponse } from '../utils/api.js'

export async function fetchVotes(mixId) {
  const response = await get(`/mixes/${mixId}/votes`)
  return readJsonResponse(response)
}

export async function submitVote(mixId, voteType) {
  const response = await post(`/mixes/${mixId}/votes`, { voteType })
  return readJsonResponse(response)
}
