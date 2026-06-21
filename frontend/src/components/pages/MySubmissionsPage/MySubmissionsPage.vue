<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
      <Heading :level="1" size="3xl" class="mb-2">
        My Submissions
      </Heading>
      <Text as="p" size="base" color="muted" class="mb-6">
        Track the review status of mixes you have submitted.
      </Text>

      <Text v-if="loading" as="p" size="sm" color="muted">
        Loading submissions...
      </Text>

      <Text v-if="error" as="p" size="sm" color="muted" class="text-red-600">
        {{ error }}
      </Text>

      <div v-if="!loading && submissions.length > 0" class="space-y-4">
        <article
          v-for="mix in submissions"
          :key="mix.id"
          class="rounded-lg bg-white p-5 shadow-md"
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

      <div v-else-if="!loading && !error" class="rounded-lg bg-white p-8 text-center shadow-md">
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
