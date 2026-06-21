<template>
  <div>
    <div v-if="showFullPageLoading" class="flex min-h-screen items-center justify-center bg-black">
      <div class="text-center">
        <div
          class="mb-4 inline-block h-12 w-12 animate-spin rounded-full border-b-2 border-[var(--color-accent)]"
        ></div>
        <p class="text-[var(--color-text-muted)]">Loading mixes...</p>
      </div>
    </div>

    <div
      v-else-if="showFullPageError"
      class="min-h-screen flex items-center justify-center"
    >
      <div class="text-center max-w-md">
        <div class="text-[var(--color-danger)] text-5xl mb-4">!</div>
        <h2 class="text-2xl font-bold text-[var(--color-text)] mb-2">
          Error Loading Mixes
        </h2>
        <p class="text-[var(--color-text-muted)] mb-4">{{ error }}</p>
        <button
          @click="fetchMixes"
          class="button-primary"
        >
          Try Again
        </button>
      </div>
    </div>

    <MixArchive
      v-else
      :mixes="mixes"
      :genres="genres"
      :search="search"
      :selected-genre="selectedGenre"
      :pagination="pagination"
      :loading="loading"
      :error="error"
      @search-change="handleSearchChange"
      @genre-change="handleGenreChange"
      @page-change="handlePageChange"
    />
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import MixArchive from '../../templates/MixArchive/MixArchive.vue'
import { fetchMixes as fetchMixesApi } from '../../../api/mixApi.js'

const mixes = ref([])
const loading = ref(true)
const error = ref(null)
const page = ref(1)
const limit = ref(6)
const search = ref('')
const selectedGenre = ref('')
const pagination = ref({
  page: 1,
  limit: 6,
  total: 0,
  totalPages: 0,
})

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

const showFullPageLoading = computed(() => loading.value && mixes.value.length === 0)
const showFullPageError = computed(() => error.value && mixes.value.length === 0)

const fetchMixes = async () => {
  loading.value = true
  error.value = null

  const params = new URLSearchParams({
    page: String(page.value),
    limit: String(limit.value),
  })

  if (selectedGenre.value) {
    params.append('genre', selectedGenre.value)
  }

  if (search.value.trim()) {
    params.append('search', search.value.trim())
  }

  try {
    const result = await fetchMixesApi(params)
    mixes.value = result.data || []
    pagination.value = result.pagination || {
      page: page.value,
      limit: limit.value,
      total: mixes.value.length,
      totalPages: 1,
    }
  } catch (err) {
    error.value = err.message || 'Failed to load mixes. Please try again later.'
    mixes.value = []
  } finally {
    loading.value = false
  }
}

const handleSearchChange = (value) => {
  search.value = value
  page.value = 1
  fetchMixes()
}

const handleGenreChange = (value) => {
  selectedGenre.value = value
  page.value = 1
  fetchMixes()
}

const handlePageChange = (newPage) => {
  if (newPage < 1 || newPage > pagination.value.totalPages) {
    return
  }

  page.value = newPage
  fetchMixes()
}

onMounted(() => {
  fetchMixes()
})
</script>
