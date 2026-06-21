<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <div class="page-header">
        <Heading :level="1" size="3xl" class="mb-2">
          My Submissions
        </Heading>
        <Text as="p" size="base" color="muted">
          Track the review status of mixes you have submitted.
        </Text>
      </div>

      <div v-if="loading" class="state-panel mb-6">
        <Text as="p" size="sm" color="muted">
          Loading submissions...
        </Text>
      </div>

      <div v-if="error" class="form-error mb-6">
        <Text as="p" size="sm" color="muted" class="text-red-700">
          {{ error }}
        </Text>
      </div>

      <div v-if="!loading && submissions.length > 0" class="space-y-4">
        <article
          v-for="mix in submissions"
          :key="mix.id"
          class="panel panel-padding"
        >
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
              <Heading :level="2" size="lg" class="mb-1">
                {{ mix.title }}
              </Heading>
              <Text as="p" size="sm" color="muted">
                {{ mix.artist }} - {{ mix.genre }} - {{ mix.platform }}
              </Text>
            </div>

            <span :class="statusClass(mix.status)">
              {{ mix.status }}
            </span>
          </div>

          <Text v-if="mix.description" as="p" size="sm" color="muted" class="mt-3">
            {{ mix.description }}
          </Text>

          <Text
            v-if="mix.status === 'rejected' && mix.reviewNote"
            as="p"
            size="sm"
            color="muted"
            class="mt-3 text-red-700"
          >
            Review note: {{ mix.reviewNote }}
          </Text>
        </article>
      </div>

      <div v-else-if="!loading && !error" class="state-panel">
        <Text as="p" size="base" color="muted">
          You have not submitted any mixes yet.
        </Text>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { get, readJsonResponse } from '../../../utils/api.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const submissions = ref([])
const loading = ref(true)
const error = ref('')

const fetchSubmissions = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await get('/my/mixes')
    submissions.value = await readJsonResponse(response)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
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

onMounted(fetchSubmissions)
</script>
