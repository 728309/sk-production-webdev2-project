<template>
  <div class="app-page">
    <Header />

    <main class="app-container">
      <div class="page-header flex flex-wrap items-end justify-between gap-3">
        <div>
          <Heading :level="1" size="3xl" class="mb-2">
            Admin Mixes
          </Heading>
          <Text as="p" size="base" color="muted">
            Manage submitted, published, and rejected mixes.
          </Text>
        </div>
      </div>

      <section class="panel panel-padding mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
        <div>
          <label for="admin-search" class="field-label-small">
            Search
          </label>
          <input
            id="admin-search"
            v-model="filters.search"
            type="search"
            class="form-input"
            placeholder="Title, artist, genre"
            @input="handleFilterChange"
          />
        </div>

        <div>
          <label for="admin-genre" class="field-label-small">
            Genre
          </label>
          <select
            id="admin-genre"
            v-model="filters.genre"
            class="form-input"
            @change="handleFilterChange"
          >
            <option value="">All genres</option>
            <option v-for="genre in genres" :key="genre" :value="genre">
              {{ genre }}
            </option>
          </select>
        </div>

        <div>
          <label for="admin-status" class="field-label-small">
            Status
          </label>
          <select
            id="admin-status"
            v-model="filters.status"
            class="form-input"
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
            class="button-secondary w-full"
            @click="resetFilters"
          >
            Reset
          </button>
        </div>
      </section>

      <div v-if="loading" class="state-panel mb-6">
        <Text as="p" size="sm" color="muted">
          Loading mixes...
        </Text>
      </div>

      <div v-if="error" class="form-error mb-6">
        <Text as="p" size="sm" color="muted" class="text-red-700">
          {{ error }}
        </Text>
      </div>

      <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_380px]">
        <div class="table-shell">
          <div class="table-scroll">
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
              class="button-secondary"
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
              class="button-secondary"
              :disabled="pagination.page >= pagination.totalPages || loading"
              @click="changePage(pagination.page + 1)"
            >
              Next
            </button>
          </div>
        </div>

        <aside class="panel panel-padding self-start">
          <Heading :level="2" size="xl" class="mb-4">
            {{ editForm.id ? 'Edit Mix' : 'Select a Mix' }}
          </Heading>

          <Text v-if="!editForm.id" as="p" size="sm" color="muted">
            Use Edit on a row to update mix details.
          </Text>

          <form v-else class="space-y-4" @submit.prevent="saveMix">
            <div>
              <label for="edit-title" class="field-label-small">Title</label>
              <input id="edit-title" v-model="editForm.title" class="form-input" type="text" />
            </div>
            <div>
              <label for="edit-artist" class="field-label-small">Artist</label>
              <input id="edit-artist" v-model="editForm.artist" class="form-input" type="text" />
            </div>
            <div>
              <label for="edit-genre" class="field-label-small">Genre</label>
              <input id="edit-genre" v-model="editForm.genre" class="form-input" type="text" />
            </div>
            <div>
              <label for="edit-platform" class="field-label-small">Platform</label>
              <input id="edit-platform" v-model="editForm.platform" class="form-input" type="text" />
            </div>
            <div>
              <label for="edit-mix-url" class="field-label-small">Mix URL</label>
              <input id="edit-mix-url" v-model="editForm.mixUrl" class="form-input" type="url" />
            </div>
            <div>
              <label for="edit-cover" class="field-label-small">Cover Image URL</label>
              <input id="edit-cover" v-model="editForm.coverImageUrl" class="form-input" type="url" />
            </div>
            <div>
              <label for="edit-duration" class="field-label-small">Duration</label>
              <input id="edit-duration" v-model="editForm.duration" class="form-input" type="text" />
            </div>
            <div>
              <label for="edit-status" class="field-label-small">Status</label>
              <select id="edit-status" v-model="editForm.status" class="form-input">
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
              <label for="edit-description" class="field-label-small">Description</label>
              <textarea id="edit-description" v-model="editForm.description" class="form-input min-h-28" rows="5"></textarea>
            </div>

            <div class="flex flex-wrap gap-3">
              <button
                type="submit"
                class="button-primary"
                :disabled="saving"
              >
                Save
              </button>
              <button
                type="button"
                class="button-secondary"
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
