<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <div class="page-header">
        <p class="page-kicker">User console</p>
        <Heading :level="1" size="3xl" class="mb-3">
          MY SUBMISSIONS
        </Heading>
        <Text as="p" size="base" color="muted">
          Track every mix you have submitted, from review queue to final decision.
        </Text>
      </div>

      <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <article
          v-for="item in summaryCards"
          :key="item.label"
          class="panel panel-padding"
        >
          <p class="page-kicker">{{ item.label }}</p>
          <p class="text-3xl font-bold text-[var(--color-text)]">{{ item.value }}</p>
        </article>
      </section>

      <div v-if="loading" class="state-panel mb-6">
        <Text as="p" size="sm" color="muted">
          Loading submissions...
        </Text>
      </div>

      <div v-if="error" class="form-error mb-6">
        <Text as="p" size="sm" color="muted" class="text-[var(--color-danger)]">
          {{ error }}
        </Text>
      </div>

      <div v-if="!loading && submissions.length > 0" class="space-y-5">
        <article
          v-for="mix in submissions"
          :key="mix.id"
          class="panel panel-padding"
        >
          <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
              <Heading :level="2" size="xl" class="mb-2">
                {{ mix.title }}
              </Heading>
              <Text as="p" size="sm" color="muted">
                {{ mix.artist }} - {{ mix.genre }} - {{ mix.platform }}
              </Text>
            </div>

            <span :class="statusClass(mix.status)">
              {{ statusLabel(mix.status) }}
            </span>
          </div>

          <Text v-if="mix.description" as="p" size="sm" color="muted" class="mt-4">
            {{ mix.description }}
          </Text>

          <div class="mt-5 rounded-md border border-[var(--color-border)] bg-[var(--color-surface-2)] p-4">
            <Text as="p" size="sm" :color="statusHelpColor(mix.status)">
              {{ statusHelperText(mix.status) }}
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

            <RouterLink
              v-if="mix.status === 'published'"
              :to="`/mixes/${mix.id}`"
              class="button-secondary mt-4"
            >
              View Public Mix
            </RouterLink>
          </div>
        </article>
      </div>

      <div v-else-if="!loading && !error" class="state-panel">
        <Text as="p" size="base" color="muted" class="mb-5">
          You have not submitted any mixes yet.
        </Text>
        <RouterLink to="/submit" class="button-primary">
          Submit a Mix
        </RouterLink>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { fetchMyMixes, fetchMyMixesSummary } from '../../../api/mixApi.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const getEmptySummary = () => ({
  total: 0,
  pending: 0,
  approved: 0,
  rejected: 0,
})

const submissions = ref([])
const summary = ref(getEmptySummary())
const loading = ref(true)
const error = ref('')

const summaryCards = computed(() => [
  { label: 'Total Submitted', value: summary.value.total },
  { label: 'Pending', value: summary.value.pending },
  { label: 'Approved', value: summary.value.approved },
  { label: 'Rejected', value: summary.value.rejected },
])

const fetchSubmissions = async () => {
  loading.value = true
  error.value = ''
  submissions.value = []
  summary.value = getEmptySummary()

  try {
    const mixesResult = await fetchMyMixes()
    submissions.value = normalizeSubmissions(mixesResult)

    try {
      const summaryResult = await fetchMyMixesSummary()
      summary.value = normalizeSummary(summaryResult)
    } catch (summaryError) {
      summary.value = calculateSummary(submissions.value)
    }
  } catch (err) {
    error.value = err.message || 'Could not load your submissions. Please try again.'
  } finally {
    loading.value = false
  }
}

const normalizeSubmissions = (value) => {
  if (Array.isArray(value)) {
    return value
  }

  return value ? [value] : []
}

const normalizeSummary = (value) => {
  return {
    total: Number(value?.total || 0),
    pending: Number(value?.pending || 0),
    approved: Number(value?.approved || 0),
    rejected: Number(value?.rejected || 0),
  }
}

const calculateSummary = (mixes) => {
  return mixes.reduce((totals, mix) => {
    totals.total += 1

    if (mix.status === 'pending') {
      totals.pending += 1
    } else if (mix.status === 'published') {
      totals.approved += 1
    } else if (mix.status === 'rejected') {
      totals.rejected += 1
    }

    return totals
  }, getEmptySummary())
}

const statusClass = (status) => {
  const classes = {
    pending: 'status-pending',
    published: 'status-approved',
    rejected: 'status-rejected',
  }

  return `status-badge ${classes[status] || ''}`
}

const statusLabel = (status) => {
  const labels = {
    pending: 'Pending',
    published: 'Approved',
    rejected: 'Rejected',
  }

  return labels[status] || status
}

const statusHelperText = (status) => {
  const messages = {
    pending: 'Waiting for admin review.',
    published: 'This mix has been approved and is visible in the public archive.',
    rejected: 'This mix was reviewed and rejected.',
  }

  return messages[status] || 'Submission status is being reviewed.'
}

const statusHelpColor = (status) => {
  return status === 'published' ? 'primary' : 'muted'
}

onMounted(fetchSubmissions)
</script>
