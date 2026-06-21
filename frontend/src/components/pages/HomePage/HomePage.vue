<template>
  <div class="app-page">
    <Header />

    <main class="app-container">
      <section class="page-header">
        <Heading :level="1" size="3xl" class="mb-2">
          SK Production Hub
        </Heading>
        <Text as="p" size="lg" color="muted" class="max-w-3xl">
          Curated mixes from producers, DJs, and selectors across the SK Production community.
        </Text>
        <RouterLink
          to="/mixes"
          class="button-primary mt-5"
        >
          Browse all mixes
        </RouterLink>
      </section>

      <section>
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
          <div>
            <Heading :level="2" size="2xl" class="mb-1">
              Featured Mixes
            </Heading>
            <Text as="p" size="sm" color="muted">
              Published mixes highlighted by the SK Production Hub team.
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
          <ArticleCard
            v-for="mix in featuredMixes"
            :key="mix.id"
            :article="mix"
          />
        </div>

        <div v-else-if="!loading && !error" class="state-panel">
          <Text as="p" size="base" color="muted">
            No featured mixes yet.
          </Text>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import { get, readJsonResponse } from '../../../utils/api.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import ArticleCard from '../../organisms/ArticleCard/ArticleCard.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const featuredMixes = ref([])
const loading = ref(true)
const error = ref('')

const fetchFeaturedMixes = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await get('/mixes/featured')
    featuredMixes.value = await readJsonResponse(response)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(fetchFeaturedMixes)
</script>
