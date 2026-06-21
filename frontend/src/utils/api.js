import config from '../config.js';

/**
 * API Utility Functions
 * 
 * Helper functions for making API requests using the app configuration
 */

/**
 * Build the full API URL for an endpoint
 * @param {string} endpoint - API endpoint (e.g., '/mixes')
 * @returns {string} Full API URL
 */
function buildApiUrl(endpoint) {
  const baseUrl = config.apiDomain.replace(/\/$/, ''); // Remove trailing slash
  const path = endpoint.replace(/^\//, ''); // Remove leading slash from endpoint
  return `${baseUrl}/${path}`;
}

function getAuthHeaders() {
  const token = localStorage.getItem('skProductionToken');

  if (!token) {
    return {};
  }

  return {
    Authorization: `Bearer ${token}`,
  };
}

export async function readJsonResponse(response) {
  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    throw new Error(data.error || 'Something went wrong. Please try again.');
  }

  return data;
}

/**
 * Make a GET request to the API
 * @param {string} endpoint - API endpoint (e.g., '/mixes')
 * @param {RequestInit} options - Fetch options
 * @returns {Promise<Response>}
 */
export async function get(endpoint, options = {}) {
  const url = buildApiUrl(endpoint);
  return fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    ...options,
  });
}

/**
 * Make a POST request to the API
 * @param {string} endpoint - API endpoint
 * @param {object} data - Request body data
 * @param {RequestInit} options - Fetch options
 * @returns {Promise<Response>}
 */
export async function post(endpoint, data, options = {}) {
  const url = buildApiUrl(endpoint);
  return fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    body: JSON.stringify(data),
    ...options,
  });
}

/**
 * Make a PUT request to the API
 * @param {string} endpoint - API endpoint
 * @param {object} data - Request body data
 * @param {RequestInit} options - Fetch options
 * @returns {Promise<Response>}
 */
export async function put(endpoint, data, options = {}) {
  const url = buildApiUrl(endpoint);
  return fetch(url, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    body: JSON.stringify(data),
    ...options,
  });
}

/**
 * Make a DELETE request to the API
 * @param {string} endpoint - API endpoint
 * @param {RequestInit} options - Fetch options
 * @returns {Promise<Response>}
 */
export async function del(endpoint, options = {}) {
  const url = buildApiUrl(endpoint);
  return fetch(url, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      ...getAuthHeaders(),
      ...options.headers,
    },
    ...options,
  });
}

/**
 * Get the full API URL for an endpoint
 * @param {string} endpoint - API endpoint
 * @returns {string} Full API URL
 */
export function getApiUrl(endpoint) {
  return buildApiUrl(endpoint);
}
