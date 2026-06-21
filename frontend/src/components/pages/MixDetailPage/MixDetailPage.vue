<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
      <RouterLink
        to="/mixes"
        class="mb-6 inline-block text-sm font-medium text-blue-600 hover:text-blue-700"
      >
        Back to mixes
      </RouterLink>

      <div v-if="loading" class="rounded-lg bg-white p-8 text-center shadow-md">
        <Text as="p" size="base" color="muted">
          Loading mix...
        </Text>
      </div>

      <div v-else-if="error" class="rounded-lg bg-white p-8 text-center shadow-md">
        <Heading :level="1" size="2xl" class="mb-2">
          Mix unavailable
        </Heading>
        <Text as="p" size="base" color="muted" class="text-red-600">
          {{ error }}
        </Text>
      </div>

      <div v-else-if="mix" class="space-y-6">
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_340px]">
          <article class="rounded-lg bg-white p-6 shadow-md">
            <CategoryBadge :genre="mix.genre" />

            <Heading :level="1" size="3xl" class="mt-4 mb-2">
              {{ mix.title }}
            </Heading>

            <Text as="p" size="lg" color="muted" class="mb-6">
              {{ mix.artist }}
            </Text>

            <div class="grid grid-cols-1 gap-4 border-y border-gray-200 py-5 sm:grid-cols-2">
              <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Platform</p>
                <p class="text-sm text-gray-900">{{ mix.platform }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Duration</p>
                <p class="text-sm text-gray-900">{{ mix.duration || 'Not listed' }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Submitted by</p>
                <p class="text-sm text-gray-900">{{ mix.submittedBy }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-gray-500">Submitted date</p>
                <p class="text-sm text-gray-900">{{ formatDate(mix.submittedDate) }}</p>
              </div>
            </div>

            <Text as="p" size="base" color="default" class="mt-6 leading-7">
              {{ mix.description }}
            </Text>

            <a
              :href="mix.mixUrl"
              target="_blank"
              rel="noreferrer"
              class="mt-6 inline-block rounded-md bg-blue-600 px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700"
            >
              Open mix
            </a>
          </article>

          <aside class="space-y-6">
            <div v-if="mix.coverImageUrl" class="overflow-hidden rounded-lg bg-white shadow-md">
              <img
                :src="mix.coverImageUrl"
                :alt="`${mix.title} cover image`"
                class="h-64 w-full object-cover"
              />
            </div>

            <VoteButtons :mix-id="mix.id" />
          </aside>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-md">
          <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
              <Heading :level="2" size="2xl">
                Comments
              </Heading>
              <Text as="p" size="sm" color="muted">
                Read reactions from the SK Production Hub community.
              </Text>
            </div>
          </div>

          <form v-if="authStore.isAuthenticated" class="mb-6" @submit.prevent="submitComment">
            <label for="comment-body" class="mb-2 block text-sm font-semibold text-gray-900">
              Add a comment
            </label>
            <textarea
              id="comment-body"
              v-model="newComment"
              rows="4"
              class="w-full rounded-md border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
              placeholder="Share a quick thought about this mix"
            ></textarea>
            <button
              type="submit"
              class="mt-3 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
              :disabled="commentSaving"
            >
              Post comment
            </button>
          </form>

          <Text v-else as="p" size="sm" color="muted" class="mb-6">
            Log in to comment.
          </Text>

          <Text v-if="commentError" as="p" size="sm" color="muted" class="mb-4 text-red-600">
            {{ commentError }}
          </Text>

          <div v-if="commentsLoading">
            <Text as="p" size="sm" color="muted">
              Loading comments...
            </Text>
          </div>

          <div v-else-if="comments.length > 0" class="space-y-4">
            <article
              v-for="comment in comments"
              :key="comment.id"
              class="rounded-lg border border-gray-200 p-4"
            >
              <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                <div>
                  <p class="text-sm font-semibold text-gray-900">{{ comment.userName }}</p>
                  <p class="text-xs text-gray-500">{{ formatDateTime(comment.createdAt) }}</p>
                </div>

                <button
                  v-if="canDeleteComment(comment)"
                  type="button"
                  class="text-sm font-semibold text-red-600 hover:text-red-700"
                  @click="deleteComment(comment.id)"
                >
                  Delete
                </button>
              </div>

              <p class="text-sm leading-6 text-gray-700">{{ comment.body }}</p>
            </article>
          </div>

          <Text v-else as="p" size="sm" color="muted">
            No comments yet.
          </Text>
        </section>
      </div>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useAuthStore } from '../../../stores/authStore.js'
import { del, get, post, readJsonResponse } from '../../../utils/api.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
import CategoryBadge from '../../molecules/CategoryBadge/CategoryBadge.vue'
import VoteButtons from '../../molecules/VoteButtons/VoteButtons.vue'

const route = useRoute()
const authStore = useAuthStore()
const mix = ref(null)
const comments = ref([])
const newComment = ref('')
const loading = ref(true)
const commentsLoading = ref(false)
const commentSaving = ref(false)
const error = ref('')
const commentError = ref('')

const mixId = computed(() => route.params.id)

const fetchMix = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await get(`/mixes/${mixId.value}`)
    mix.value = await readJsonResponse(response)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const fetchComments = async () => {
  commentsLoading.value = true
  commentError.value = ''

  try {
    const response = await get(`/mixes/${mixId.value}/comments`)
    comments.value = await readJsonResponse(response)
  } catch (err) {
    commentError.value = err.message
  } finally {
    commentsLoading.value = false
  }
}

const submitComment = async () => {
  if (!newComment.value.trim()) {
    commentError.value = 'Comment body is required'
    return
  }

  commentSaving.value = true
  commentError.value = ''

  try {
    const response = await post(`/mixes/${mixId.value}/comments`, {
      body: newComment.value,
    })
    const createdComment = await readJsonResponse(response)
    comments.value = [...comments.value, createdComment]
    newComment.value = ''
  } catch (err) {
    commentError.value = err.message
  } finally {
    commentSaving.value = false
  }
}

const deleteComment = async (commentId) => {
  commentError.value = ''

  try {
    const response = await del(`/comments/${commentId}`)
    await readJsonResponse(response)
    comments.value = comments.value.filter((comment) => comment.id !== commentId)
  } catch (err) {
    commentError.value = err.message
  }
}

const canDeleteComment = (comment) => {
  return authStore.isAdmin || Number(comment.userId) === Number(authStore.user?.id)
}

const formatDate = (value) => {
  if (!value) {
    return 'Not listed'
  }

  return new Date(`${value}T00:00:00`).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const formatDateTime = (value) => {
  if (!value) {
    return ''
  }

  return new Date(value.replace(' ', 'T')).toLocaleString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const loadPage = () => {
  fetchMix()
  fetchComments()
}

onMounted(loadPage)
watch(mixId, loadPage)
</script>
