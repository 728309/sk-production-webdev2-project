<template>
  <div class="app-page">
    <Header />

    <main class="flex flex-1 items-center justify-center px-4 py-10 sm:py-14">
      <section class="panel panel-padding w-full max-w-md">
        <Heading :level="1" size="2xl" class="mb-2">
          LOGIN TO SK HUB
        </Heading>
        <Text as="p" size="sm" color="muted" class="mb-6">
          Sign in to submit, vote, and leave listener notes.
        </Text>

        <form class="space-y-4" @submit.prevent="handleSubmit">
          <div>
            <label for="email" class="field-label">
              Email
            </label>
            <input
              id="email"
              v-model="email"
              type="email"
              autocomplete="email"
              class="form-input"
              required
            />
          </div>

          <div>
            <label for="password" class="field-label">
              Password
            </label>
            <input
              id="password"
              v-model="password"
              type="password"
              autocomplete="current-password"
              class="form-input"
              required
            />
          </div>

          <div v-if="error" class="form-error">
            <Text as="p" size="sm" color="muted" class="text-[var(--color-danger)]">
              {{ error }}
            </Text>
          </div>

          <button
            type="submit"
            class="button-primary w-full"
            :disabled="loading"
          >
            {{ loading ? 'Logging in...' : 'Login' }}
          </button>
        </form>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../../stores/authStore.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  error.value = ''
  loading.value = true

  try {
    await authStore.login(email.value, password.value)
    router.push('/mixes')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}
</script>
