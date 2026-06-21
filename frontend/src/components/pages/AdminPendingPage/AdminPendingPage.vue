<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
      <Heading :level="1" size="3xl" class="mb-2">
        Admin Pending
      </Heading>
      <Text as="p" size="base" color="muted" class="mb-6">
        Review mixes waiting for approval.
      </Text>

      <Text v-if="loading" as="p" size="sm" color="muted">
        Loading pending mixes...
      </Text>

      <Text v-if="error" as="p" size="sm" color="muted" class="text-red-600">
        {{ error }}
      </Text>

      <div v-if="!loading && pendingMixes.length > 0" class="space-y-4">
        <article
          v-for="mix in pendingMixes"
          :key="mix.id"
          class="rounded-lg bg-white p-5 shadow-md"
        >
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
              <Heading :level="2" size="lg" class="mb-1">
                {{ mix.title }}
              </Heading>
              <Text as="p" size="sm" color="muted">
                {{ mix.artist }} - {{ mix.genre }} - submitted by {{ mix.submittedBy }}
              </Text>
            </div>

            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold uppercase text-yellow-800">
              {{ mix.status }}
            </span>
          </div>

          <Text v-if="mix.description" as="p" size="sm" color="muted" class="mt-3">
            {{ mix.description }}
          </Text>

          <div class="mt-4 flex flex-wrap gap-3">
            <button
              type="button"
              class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-green-700"
              @click="approveMix(mix.id)"
            >
              Approve
            </button>
            <button
              type="button"
              class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700"
              @click="rejectMix(mix.id)"
            >
              Reject
            </button>
          </div>
        </article>
      </div>

      <div v-else-if="!loading && !error" class="rounded-lg bg-white p-8 text-center shadow-md">
        <Text as="p" size="base" color="muted">
          No pending mixes to review.
        </Text>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { get, put, readJsonResponse } from '../../../utils/api.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const pendingMixes = ref([])
const loading = ref(true)
const error = ref('')

const fetchPendingMixes = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await get('/admin/mixes/pending')
    pendingMixes.value = await readJsonResponse(response)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const approveMix = async (id) => {
  try {
    const response = await put(`/admin/mixes/${id}/approve`, {})
    await readJsonResponse(response)
    fetchPendingMixes()
  } catch (err) {
    error.value = err.message
  }
}

const rejectMix = async (id) => {
  const reviewNote = prompt('Add a short review note for this rejection:')

  if (!reviewNote) {
    return
  }

  try {
    const response = await put(`/admin/mixes/${id}/reject`, { reviewNote })
    await readJsonResponse(response)
    fetchPendingMixes()
  } catch (err) {
    error.value = err.message
  }
}

onMounted(fetchPendingMixes)
</script>
