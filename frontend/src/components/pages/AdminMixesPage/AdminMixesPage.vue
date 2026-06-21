<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
      <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
        <div>
          <Heading :level="1" size="3xl" class="mb-2">
            Admin Mixes
          </Heading>
          <Text as="p" size="base" color="muted">
            Manage submitted, published, and rejected mixes.
          </Text>
        </div>
      </div>

      <section class="mb-6 grid grid-cols-1 gap-4 rounded-lg bg-white p-5 shadow-md md:grid-cols-4">
        <div>
          <label for="admin-search" class="mb-1 block text-xs font-semibold uppercase text-gray-500">
            Search
          </label>
          <input
            id="admin-search"
            v-model="filters.search"
            type="search"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            placeholder="Title, artist, genre"
            @input="handleFilterChange"
          />
        </div>

        <div>
          <label for="admin-genre" class="mb-1 block text-xs font-semibold uppercase text-gray-500">
            Genre
          </label>
          <select
            id="admin-genre"
            v-model="filters.genre"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            @change="handleFilterChange"
          >
            <option value="">All genres</option>
            <option v-for="genre in genres" :key="genre" :value="genre">
              {{ genre }}
            </option>
          </select>
        </div>

        <div>
          <label for="admin-status" class="mb-1 block text-xs font-semibold uppercase text-gray-500">
            Status
          </label>
          <select
            id="admin-status"
            v-model="filters.status"
            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
            @change="handleFilterChange"
          >
            <option value="">All statuses</option>
            <option value="pending">Pending</option>
            <option value="published">Published</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="flex items-end">
          <button
            type="button"
            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100"
            @click="resetFilters"
          >
            Reset
          </button>
        </div>
      </section>

      <Text v-if="loading" as="p" size="sm" color="muted" class="mb-4">
        Loading mixes...
      </Text>

      <Text v-if="error" as="p" size="sm" color="muted" class="mb-4 text-red-600">
        {{ error }}
      </Text>

      <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_380px]">
        <div class="overflow-hidden rounded-lg bg-white shadow-md">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Title</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Artist</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Genre</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Featured</th>
                  <th class="min-w-64 px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="mix in mixes" :key="mix.id">
                  <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ mix.title }}</td>
                  <td class="px-4 py-3 text-sm text-gray-700">{{ mix.artist }}</td>
                  <td class="px-4 py-3 text-sm text-gray-700">{{ mix.genre }}</td>
                  <td class="px-4 py-3">
                    <span :class="statusClass(mix.status)">
                      {{ mix.status }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-sm text-gray-700">
                    {{ mix.featured ? 'Yes' : 'No' }}
                  </td>
                  <td class="min-w-64 px-4 py-3 align-top">
                    <div class="flex min-w-56 flex-wrap items-center gap-2">
                      <button
                        type="button"
                        class="action-button border-gray-300 text-gray-700 hover:bg-gray-100"
                        @click="startEdit(mix)"
                      >
                        Edit
                      </button>
                      <button
                        type="button"
                        class="action-button min-w-24 border-blue-300 text-blue-700 hover:bg-blue-50"
                        @click="toggleFeatured(mix)"
                      >
                        {{ mix.featured ? 'Unfeature' : 'Feature' }}
                      </button>
                      <button
                        type="button"
                        class="action-button border-red-300 text-red-700 hover:bg-red-50"
                        @click="deleteMix(mix)"
                      >
                        Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="!loading && mixes.length === 0" class="p-8 text-center">
            <Text as="p" size="base" color="muted">
              No mixes found.
            </Text>
          </div>

          <div
            v-if="pagination.totalPages > 1"
            class="flex flex-wrap items-center justify-center gap-3 border-t border-gray-200 p-4"
          >
            <button
              type="button"
              class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
              :disabled="pagination.page <= 1 || loading"
              @click="changePage(pagination.page - 1)"
            >
              Previous
            </button>
            <Text as="span" size="sm" color="muted">
              Page {{ pagination.page }} of {{ pagination.totalPages }}
            </Text>
            <button
              type="button"
              class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50"
              :disabled="pagination.page >= pagination.totalPages || loading"
              @click="changePage(pagination.page + 1)"
            >
              Next
            </button>
          </div>
        </div>

        <aside class="rounded-lg bg-white p-5 shadow-md">
          <Heading :level="2" size="xl" class="mb-4">
            {{ editForm.id ? 'Edit Mix' : 'Select a Mix' }}
          </Heading>

          <Text v-if="!editForm.id" as="p" size="sm" color="muted">
            Use Edit on a row to update mix details.
          </Text>

          <form v-else class="space-y-4" @submit.prevent="saveMix">
            <div>
              <label for="edit-title" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Title</label>
              <input id="edit-title" v-model="editForm.title" class="admin-input" type="text" />
            </div>
            <div>
              <label for="edit-artist" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Artist</label>
              <input id="edit-artist" v-model="editForm.artist" class="admin-input" type="text" />
            </div>
            <div>
              <label for="edit-genre" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Genre</label>
              <input id="edit-genre" v-model="editForm.genre" class="admin-input" type="text" />
            </div>
            <div>
              <label for="edit-platform" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Platform</label>
              <input id="edit-platform" v-model="editForm.platform" class="admin-input" type="text" />
            </div>
            <div>
              <label for="edit-mix-url" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Mix URL</label>
              <input id="edit-mix-url" v-model="editForm.mixUrl" class="admin-input" type="url" />
            </div>
            <div>
              <label for="edit-cover" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Cover Image URL</label>
              <input id="edit-cover" v-model="editForm.coverImageUrl" class="admin-input" type="url" />
            </div>
            <div>
              <label for="edit-duration" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Duration</label>
              <input id="edit-duration" v-model="editForm.duration" class="admin-input" type="text" />
            </div>
            <div>
              <label for="edit-status" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Status</label>
              <select id="edit-status" v-model="editForm.status" class="admin-input">
                <option value="pending">Pending</option>
                <option value="published">Published</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>
            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
              <input v-model="editForm.featured" type="checkbox" class="h-4 w-4 rounded border-gray-300" />
              Featured
            </label>
            <div>
              <label for="edit-description" class="mb-1 block text-xs font-semibold uppercase text-gray-500">Description</label>
              <textarea id="edit-description" v-model="editForm.description" class="admin-input min-h-28" rows="5"></textarea>
            </div>

            <div class="flex flex-wrap gap-3">
              <button
                type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="saving"
              >
                Save
              </button>
              <button
                type="button"
                class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100"
                @click="clearEdit"
              >
                Cancel
              </button>
            </div>
          </form>
        </aside>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { del, get, put, readJsonResponse } from '../../../utils/api.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const genres = [
  'Afrobeat',
  'Techno',
  'House',
  'Hip-Hop',
  'Amapiano',
  'R&B',
  'Dancehall',
  'Drum and Bass',
]

const mixes = ref([])
const loading = ref(true)
const saving = ref(false)
const error = ref('')
const page = ref(1)
const limit = ref(6)
const pagination = ref({
  page: 1,
  limit: 6,
  total: 0,
  totalPages: 0,
})
const filters = reactive({
  search: '',
  genre: '',
  status: '',
})
const editForm = reactive(getEmptyForm())

function getEmptyForm() {
  return {
    id: null,
    title: '',
    artist: '',
    genre: '',
    platform: '',
    mixUrl: '',
    coverImageUrl: '',
    duration: '',
    description: '',
    status: 'pending',
    featured: false,
  }
}

const fetchMixes = async () => {
  loading.value = true
  error.value = ''

  const params = new URLSearchParams({
    page: String(page.value),
    limit: String(limit.value),
  })

  if (filters.search.trim()) {
    params.append('search', filters.search.trim())
  }

  if (filters.genre) {
    params.append('genre', filters.genre)
  }

  if (filters.status) {
    params.append('status', filters.status)
  }

  try {
    const response = await get(`/admin/mixes?${params.toString()}`)
    const result = await readJsonResponse(response)
    mixes.value = result.data || []
    pagination.value = result.pagination || pagination.value
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const handleFilterChange = () => {
  page.value = 1
  fetchMixes()
}

const resetFilters = () => {
  filters.search = ''
  filters.genre = ''
  filters.status = ''
  page.value = 1
  fetchMixes()
}

const changePage = (newPage) => {
  if (newPage < 1 || newPage > pagination.value.totalPages) {
    return
  }

  page.value = newPage
  fetchMixes()
}

const startEdit = (mix) => {
  Object.assign(editForm, {
    id: mix.id,
    title: mix.title,
    artist: mix.artist,
    genre: mix.genre,
    platform: mix.platform,
    mixUrl: mix.mixUrl,
    coverImageUrl: mix.coverImageUrl || '',
    duration: mix.duration || '',
    description: mix.description || '',
    status: mix.status,
    featured: Boolean(mix.featured),
  })
}

const clearEdit = () => {
  Object.assign(editForm, getEmptyForm())
}

const saveMix = async () => {
  saving.value = true
  error.value = ''

  try {
    const response = await put(`/admin/mixes/${editForm.id}`, { ...editForm })
    const updatedMix = await readJsonResponse(response)
    mixes.value = mixes.value.map((mix) => (mix.id === updatedMix.id ? updatedMix : mix))
    startEdit(updatedMix)
  } catch (err) {
    error.value = err.message
  } finally {
    saving.value = false
  }
}

const deleteMix = async (mix) => {
  if (!confirm(`Delete "${mix.title}"?`)) {
    return
  }

  error.value = ''

  try {
    const response = await del(`/admin/mixes/${mix.id}`)
    await readJsonResponse(response)
    if (editForm.id === mix.id) {
      clearEdit()
    }
    fetchMixes()
  } catch (err) {
    error.value = err.message
  }
}

const toggleFeatured = async (mix) => {
  error.value = ''
  const endpoint = mix.featured ? 'unfeature' : 'feature'

  try {
    const response = await put(`/admin/mixes/${mix.id}/${endpoint}`, {})
    const updatedMix = await readJsonResponse(response)
    mixes.value = mixes.value.map((item) => (item.id === updatedMix.id ? updatedMix : item))
    if (editForm.id === updatedMix.id) {
      startEdit(updatedMix)
    }
  } catch (err) {
    error.value = err.message
  }
}

const statusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    published: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
  }

  return `rounded-full px-3 py-1 text-xs font-semibold uppercase ${classes[status] || 'bg-gray-100 text-gray-800'}`
}

onMounted(fetchMixes)
</script>

<style scoped>
.admin-input {
  width: 100%;
  border-radius: 0.375rem;
  border: 1px solid rgb(209 213 219);
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  color: rgb(17 24 39);
}

.admin-input:focus {
  border-color: rgb(59 130 246);
  outline: none;
  box-shadow: 0 0 0 2px rgb(191 219 254);
}

.action-button {
  display: inline-flex;
  min-height: 2rem;
  align-items: center;
  justify-content: center;
  border-width: 1px;
  border-radius: 0.375rem;
  padding: 0.375rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  line-height: 1;
  transition: background-color 150ms ease, color 150ms ease;
  white-space: nowrap;
}
</style>
