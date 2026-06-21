import { del, get, post, readJsonResponse } from '../utils/api.js'

export async function fetchComments(mixId) {
  const response = await get(`/mixes/${mixId}/comments`)
  return readJsonResponse(response)
}

export async function postComment(mixId, body) {
  const response = await post(`/mixes/${mixId}/comments`, { body })
  return readJsonResponse(response)
}

export async function deleteComment(commentId) {
  const response = await del(`/comments/${commentId}`)
  return readJsonResponse(response)
}
