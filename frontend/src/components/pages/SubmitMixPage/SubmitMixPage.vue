<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
      <Heading :level="1" size="3xl" class="mb-2">
        Submit a Mix
      </Heading>
      <Text as="p" size="base" color="muted" class="mb-6">
        Submitted mixes are reviewed before they appear in the public archive.
      </Text>

      <form class="rounded-lg bg-white p-6 shadow-md space-y-4" @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="title" class="mb-1 block text-sm font-medium text-gray-700">Title</label>
            <input id="title" v-model="form.title" class="input" required />
          </div>

          <div>
            <label for="artist" class="mb-1 block text-sm font-medium text-gray-700">Artist</label>
            <input id="artist" v-model="form.artist" class="input" required />
          </div>

          <div>
            <label for="genre" class="mb-1 block text-sm font-medium text-gray-700">Genre</label>
            <input id="genre" v-model="form.genre" class="input" required />
          </div>

          <div>
            <label for="platform" class="mb-1 block text-sm font-medium text-gray-700">Platform</label>
            <input id="platform" v-model="form.platform" class="input" required />
          </div>

          <div>
            <label for="mixUrl" class="mb-1 block text-sm font-medium text-gray-700">Mix URL</label>
            <input id="mixUrl" v-model="form.mixUrl" type="url" class="input" required />
          </div>

          <div>
            <label for="coverImageUrl" class="mb-1 block text-sm font-medium text-gray-700">Cover Image URL</label>
            <input id="coverImageUrl" v-model="form.coverImageUrl" type="url" class="input" />
          </div>

          <div>
            <label for="duration" class="mb-1 block text-sm font-medium text-gray-700">Duration</label>
            <input id="duration" v-model="form.duration" class="input" placeholder="42:18" />
          </div>
        </div>

        <div>
          <label for="description" class="mb-1 block text-sm font-medium text-gray-700">Description</label>
          <textarea id="description" v-model="form.description" rows="4" class="input"></textarea>
        </div>

        <Text v-if="error" as="p" size="sm" color="muted" class="text-red-600">
          {{ error }}
        </Text>

        <button
          type="submit"
          class="rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
          :disabled="loading"
        >
          {{ loading ? 'Submitting...' : 'Submit mix' }}
        </button>
      </form>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { post, readJsonResponse } from '../../../utils/api.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const router = useRouter()

const loading = ref(false)
const error = ref('')
const form = reactive({
  title: '',
  artist: '',
  genre: '',
  platform: '',
  mixUrl: '',
  coverImageUrl: '',
  duration: '',
  description: '',
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await post('/mixes', { ...form })
    await readJsonResponse(response)
    router.push('/my-submissions')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.input {
  width: 100%;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
  background: white;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  color: #111827;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

.input:focus {
  border-color: #3b82f6;
  outline: none;
  box-shadow: 0 0 0 2px rgb(191 219 254);
}
</style>
