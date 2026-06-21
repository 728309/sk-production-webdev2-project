<script setup>
import { onErrorCaptured, onMounted, ref, watch } from 'vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'
import { useAuthStore } from './stores/authStore.js'
import Footer from './components/organisms/Footer/Footer.vue'
import Header from './components/organisms/Header/Header.vue'

const authStore = useAuthStore()
const route = useRoute()
const appError = ref('')

onMounted(() => {
  authStore.fetchMe().catch(() => {
    authStore.logout()
  })
})

watch(
  () => route.fullPath,
  () => {
    appError.value = ''
  },
)

onErrorCaptured((error) => {
  console.error(error)
  appError.value = 'Something went wrong while loading this page.'
  return false
})
</script>

<template>
  <RouterView v-if="!appError" />

  <div v-else class="app-page">
    <Header />
    <main class="app-container-medium">
      <section class="state-panel">
        <p class="page-kicker">Page error</p>
        <h1 class="mb-3 text-2xl font-bold text-[var(--color-text)]">
          Page could not load
        </h1>
        <p class="mb-5 text-[var(--color-text-muted)]">
          {{ appError }}
        </p>
        <RouterLink to="/" class="button-primary">
          Go Home
        </RouterLink>
      </section>
    </main>
    <Footer />
  </div>
</template>
