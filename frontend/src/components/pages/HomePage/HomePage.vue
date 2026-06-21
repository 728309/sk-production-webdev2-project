<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
      <section class="mb-10">
        <Heading :level="1" size="3xl" class="mb-2">
          SK Production Hub
        </Heading>
        <Text as="p" size="lg" color="muted" class="max-w-3xl">
          Curated mixes from producers, DJs, and selectors across the SK Production community.
        </Text>
        <RouterLink
          to="/mixes"
          class="mt-5 inline-block rounded-md bg-blue-600 px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700"
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

        <Text v-if="error" as="p" size="sm" color="muted" class="text-red-600">
          {{ error }}
        </Text>

        <div
          v-if="!loading && featuredMixes.length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <ArticleCard
            v-for="mix in featuredMixes"
            :key="mix.id"
            :article="mix"
          />
        </div>

        <div v-else-if="!loading && !error" class="rounded-lg bg-white p-8 text-center shadow-md">
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
