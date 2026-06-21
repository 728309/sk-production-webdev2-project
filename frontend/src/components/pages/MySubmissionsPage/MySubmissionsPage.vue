<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <div class="page-header">
        <p class="page-kicker">User console</p>
        <Heading :level="1" size="3xl" class="mb-2">
          MY SUBMISSIONS
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
            class="mt-3 text-[var(--color-danger)]"
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
import { fetchMyMixes } from '../../../api/mixApi.js'
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
    submissions.value = await fetchMyMixes()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const statusClass = (status) => {
  const classes = {
    pending: 'status-pending',
    published: 'status-published',
    rejected: 'status-rejected',
  }

  return `status-badge ${classes[status] || ''}`
}

onMounted(fetchSubmissions)
</script>
