<template>
  <div class="app-page">
    <Header />

    <main class="app-container max-w-6xl">
      <RouterLink
        to="/mixes"
        class="mb-6 inline-block text-sm font-bold uppercase text-[var(--color-accent)] hover:text-[var(--color-accent-bright)]"
      >
        Back to mixes
      </RouterLink>

      <div v-if="loading" class="state-panel">
        <Text as="p" size="base" color="muted">
          Loading mix...
        </Text>
      </div>

      <div v-else-if="error" class="state-panel">
        <Heading :level="1" size="2xl" class="mb-2">
          Mix unavailable
        </Heading>
        <Text as="p" size="base" color="muted" class="text-red-600">
          {{ error }}
        </Text>
      </div>

      <div v-else-if="mix" class="space-y-6">
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
          <article class="panel panel-padding">
            <div v-if="embedUrl" class="mb-6 overflow-hidden rounded-lg border border-[var(--color-border-strong)] bg-black shadow-[0_0_24px_rgba(124,255,65,0.12)]">
              <iframe
                :src="embedUrl"
                :title="`${mix.title} player`"
                class="h-80 w-full"
                loading="lazy"
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                allowfullscreen
              ></iframe>
            </div>

            <GenreBadge :genre="mix.genre" />

            <Heading :level="1" size="3xl" class="mt-4 mb-2">
              {{ mix.title }}
            </Heading>

            <Text as="p" size="lg" color="muted" class="mb-6">
              {{ mix.artist }}
            </Text>

            <div class="grid grid-cols-1 gap-4 border-y border-[var(--color-border)] py-5 sm:grid-cols-2">
              <div>
                <p class="text-xs font-semibold uppercase text-[var(--color-text-muted)]">Platform</p>
                <p class="text-sm text-[var(--color-text)]">{{ mix.platform }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-[var(--color-text-muted)]">Duration</p>
                <p class="text-sm text-[var(--color-text)]">{{ mix.duration || 'Not listed' }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-[var(--color-text-muted)]">Submitted by</p>
                <p class="text-sm text-[var(--color-text)]">{{ mix.submittedBy }}</p>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-[var(--color-text-muted)]">Submitted date</p>
                <p class="text-sm text-[var(--color-text)]">{{ formatDate(mix.submittedDate) }}</p>
              </div>
            </div>

            <Text as="p" size="base" color="default" class="mt-6 leading-7">
              {{ mix.description }}
            </Text>

            <a
              :href="mix.mixUrl"
              target="_blank"
              rel="noreferrer"
              class="button-primary mt-6"
            >
              Open mix
            </a>
          </article>

          <aside class="space-y-6">
            <div v-if="mix.coverImageUrl" class="panel overflow-hidden">
              <img
                :src="mix.coverImageUrl"
                :alt="`${mix.title} cover image`"
                class="aspect-video w-full object-cover lg:aspect-square"
              />
            </div>

            <VoteButtons :mix-id="mix.id" />
          </aside>
        </section>

        <section class="panel panel-padding">
          <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
              <Heading :level="2" size="2xl">
                LISTENER NOTES
              </Heading>
              <Text as="p" size="sm" color="muted">
                Read reactions from the SK HUB community.
              </Text>
            </div>
          </div>

          <form v-if="authStore.isAuthenticated" class="mb-6" @submit.prevent="submitComment">
            <label for="comment-body" class="mb-2 block text-sm font-semibold uppercase text-[var(--color-text-soft)]">
              Add a comment
            </label>
            <textarea
              id="comment-body"
              v-model="newComment"
              rows="4"
              class="form-input"
              placeholder="Share a quick thought about this mix"
            ></textarea>
            <button
              type="submit"
              class="button-primary mt-3"
              :disabled="commentSaving"
            >
              Post comment
            </button>
          </form>

          <Text v-else as="p" size="sm" color="muted" class="mb-6">
            Log in to comment.
          </Text>

          <div v-if="commentError" class="form-error mb-4">
            <Text as="p" size="sm" color="muted" class="text-red-700">
              {{ commentError }}
            </Text>
          </div>

          <div v-if="commentsLoading">
            <Text as="p" size="sm" color="muted">
              Loading comments...
            </Text>
          </div>

          <div v-else-if="comments.length > 0" class="space-y-4">
            <article
              v-for="comment in comments"
              :key="comment.id"
              class="rounded-lg border border-[var(--color-border)] bg-[var(--color-surface-2)] p-4"
            >
              <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                <div>
                  <p class="text-sm font-semibold text-[var(--color-text)]">{{ comment.userName }}</p>
                  <p class="text-xs text-[var(--color-text-muted)]">{{ formatDateTime(comment.createdAt) }}</p>
                </div>

                <button
                  v-if="canDeleteComment(comment)"
                  type="button"
                  class="text-sm font-semibold uppercase text-[var(--color-danger)] hover:text-red-300"
                  @click="deleteComment(comment.id)"
                >
                  Delete
                </button>
              </div>

              <p class="text-sm leading-6 text-[var(--color-text-soft)]">{{ comment.body }}</p>
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
import { deleteComment as deleteCommentApi, fetchComments as fetchCommentsApi, postComment } from '../../../api/commentApi.js'
import { fetchMixById } from '../../../api/mixApi.js'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
import GenreBadge from '../../molecules/GenreBadge/GenreBadge.vue'
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
const embedUrl = computed(() => getEmbedUrl(mix.value?.mixUrl || ''))

const fetchMix = async () => {
  loading.value = true
  error.value = ''

  try {
    mix.value = await fetchMixById(mixId.value)
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
    comments.value = await fetchCommentsApi(mixId.value)
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
    const createdComment = await postComment(mixId.value, newComment.value)
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
    await deleteCommentApi(commentId)
    comments.value = comments.value.filter((comment) => comment.id !== commentId)
  } catch (err) {
    commentError.value = err.message
  }
}

const canDeleteComment = (comment) => {
  return authStore.isAdmin || Number(comment.userId) === Number(authStore.user?.id)
}

const getEmbedUrl = (value) => {
  try {
    const url = new URL(value)
    const hostname = url.hostname.replace(/^www\./, '').toLowerCase()

    if (url.protocol !== 'https:' && url.protocol !== 'http:') {
      return ''
    }

    if (hostname === 'soundcloud.com') {
      return `https://w.soundcloud.com/player/?url=${encodeURIComponent(url.toString())}`
    }

    if (hostname === 'mixcloud.com') {
      const feedPath = url.pathname.endsWith('/') ? url.pathname : `${url.pathname}/`
      return `https://www.mixcloud.com/widget/iframe/?feed=${encodeURIComponent(feedPath)}`
    }

    if (hostname === 'youtube.com' || hostname === 'youtu.be') {
      const videoId = hostname === 'youtu.be'
        ? url.pathname.replace('/', '')
        : url.searchParams.get('v')

      return videoId ? `https://www.youtube.com/embed/${encodeURIComponent(videoId)}` : ''
    }
  } catch (error) {
    return ''
  }

  return ''
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
