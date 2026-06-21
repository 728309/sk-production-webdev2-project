<template>
  <div class="app-page">
    <Header />

    <main class="app-container">
      <section class="page-header py-8 sm:py-12 lg:py-16">
        <p class="page-kicker">Terminal archive online</p>
        <Heading :level="1" size="3xl" class="max-w-4xl text-4xl sm:text-5xl">
          FRESH MIXES, LIVE FROM <span class="terminal-text">SK HUB.</span>
        </Heading>
        <Text as="p" size="lg" color="secondary" class="mt-5 max-w-3xl leading-8">
          Discover curated DJ mixes from producers, selectors, and underground artists.
        </Text>
        <div class="mt-7 flex flex-wrap gap-3">
          <RouterLink to="/mixes" class="button-primary">
            Explore Mixes
          </RouterLink>
          <RouterLink to="/submit" class="button-secondary">
            Submit Your Mix
          </RouterLink>
        </div>
      </section>

      <section class="mb-12">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
          <div>
            <Heading :level="2" size="2xl" class="mb-1">
              Featured Selections
            </Heading>
            <Text as="p" size="sm" color="muted">
              Published mixes highlighted by the SK HUB team.
            </Text>
          </div>
        </div>

        <Text v-if="loading" as="p" size="sm" color="muted">
          Loading featured mixes...
        </Text>

        <div v-if="error" class="form-error mb-6">
          <Text as="p" size="sm" color="muted" class="text-red-700">
            {{ error }}
          </Text>
        </div>

        <div
          v-if="!loading && featuredMixes.length > 0"
          class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3"
        >
          <MixCard
            v-for="mix in featuredMixes"
            :key="mix.id"
            :mix="mix"
          />
        </div>

        <div v-else-if="!loading && !error" class="state-panel">
          <Text as="p" size="base" color="muted">
            No featured mixes yet.
          </Text>
        </div>
      </section>

      <section class="mb-12">
        <div class="mb-5">
          <Heading :level="2" size="2xl" class="mb-1">
            Latest Approved Mixes
          </Heading>
          <Text as="p" size="sm" color="muted">
            Freshly approved archive entries ready for playback.
          </Text>
        </div>

        <div
          v-if="!loading && latestMixes.length > 0"
          class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3"
        >
          <MixCard
            v-for="mix in latestMixes"
            :key="mix.id"
            :mix="mix"
          />
        </div>

        <div v-else-if="!loading && !error" class="state-panel">
          <Text as="p" size="base" color="muted">
            No approved mixes yet.
          </Text>
        </div>
      </section>

      <section class="panel panel-padding flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="page-kicker">Signal accepted</p>
          <Heading :level="2" size="2xl" class="mb-2">
            Send a Signal to SK HUB
          </Heading>
          <Text as="p" size="sm" color="muted" class="max-w-2xl">
            Send in a set for review. Approved submissions join the public archive.
          </Text>
        </div>
        <RouterLink to="/submit" class="button-secondary shrink-0">
          Submit Your Mix
        </RouterLink>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import MixCard from '../../organisms/MixCard/MixCard.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
import { fetchFeaturedMixes as fetchFeaturedMixesApi, fetchMixes as fetchMixesApi } from '../../../api/mixApi.js'

const featuredMixes = ref([])
const latestMixes = ref([])
const loading = ref(true)
const error = ref('')

const fetchHomeMixes = async () => {
  loading.value = true
  error.value = ''

  try {
    const latestParams = new URLSearchParams({
      page: '1',
      limit: '3',
    })

    const [featuredResult, latestResult] = await Promise.all([
      fetchFeaturedMixesApi(),
      fetchMixesApi(latestParams),
    ])

    featuredMixes.value = featuredResult
    latestMixes.value = latestResult.data || []
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchHomeMixes)
</script>
