<template>
  <div class="app-page">
    <Header />

    <main class="app-container-narrow">
      <div class="page-header">
        <Heading :level="1" size="3xl" class="mb-2">
          Submit a Mix
        </Heading>
        <Text as="p" size="base" color="muted">
          Submitted mixes are reviewed before they appear in the public archive.
        </Text>
      </div>

      <form class="panel panel-padding space-y-5" @submit.prevent="handleSubmit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="title" class="field-label">Title</label>
            <input id="title" v-model="form.title" class="form-input" required />
          </div>

          <div>
            <label for="artist" class="field-label">Artist</label>
            <input id="artist" v-model="form.artist" class="form-input" required />
          </div>

          <div>
            <label for="genre" class="field-label">Genre</label>
            <input id="genre" v-model="form.genre" class="form-input" required />
          </div>

          <div>
            <label for="platform" class="field-label">Platform</label>
            <input id="platform" v-model="form.platform" class="form-input" required />
          </div>

          <div>
            <label for="mixUrl" class="field-label">Mix URL</label>
            <input id="mixUrl" v-model="form.mixUrl" type="url" class="form-input" required />
          </div>

          <div>
            <label for="coverImageUrl" class="field-label">Cover Image URL</label>
            <input id="coverImageUrl" v-model="form.coverImageUrl" type="url" class="form-input" />
          </div>

          <div>
            <label for="duration" class="field-label">Duration</label>
            <input id="duration" v-model="form.duration" class="form-input" placeholder="42:18" />
          </div>
        </div>

        <div>
          <label for="description" class="field-label">Description</label>
          <textarea id="description" v-model="form.description" rows="4" class="form-input"></textarea>
        </div>

        <div v-if="error" class="form-error">
          <Text as="p" size="sm" color="muted" class="text-red-700">
            {{ error }}
          </Text>
        </div>

        <button
          type="submit"
          class="button-primary w-full sm:w-auto"
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
