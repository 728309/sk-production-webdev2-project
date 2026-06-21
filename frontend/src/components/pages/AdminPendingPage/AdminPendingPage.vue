<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <div class="page-header">
        <Heading :level="1" size="3xl" class="mb-2">
          Admin Pending
        </Heading>
        <Text as="p" size="base" color="muted">
          Review mixes waiting for approval.
        </Text>
      </div>

      <div v-if="loading" class="state-panel mb-6">
        <Text as="p" size="sm" color="muted">
          Loading pending mixes...
        </Text>
      </div>

      <div v-if="error" class="form-error mb-6">
        <Text as="p" size="sm" color="muted" class="text-red-700">
          {{ error }}
        </Text>
      </div>

      <div v-if="!loading && pendingMixes.length > 0" class="space-y-4">
        <article
          v-for="mix in pendingMixes"
          :key="mix.id"
          class="panel panel-padding"
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
              class="button-success"
              @click="approveMix(mix.id)"
            >
              Approve
            </button>
            <button
              type="button"
              class="button-danger"
              @click="rejectMix(mix.id)"
            >
              Reject
            </button>
          </div>
        </article>
      </div>

      <div v-else-if="!loading && !error" class="state-panel">
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
