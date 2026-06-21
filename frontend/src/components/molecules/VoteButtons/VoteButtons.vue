<template>
  <section class="panel panel-padding">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <p class="text-sm font-semibold uppercase text-[var(--color-text)]">Mix rating</p>
        <p class="text-sm text-[var(--color-text-muted)]">
          {{ votes.likes }} likes &middot; {{ votes.dislikes }} dislikes
        </p>
      </div>

      <div v-if="authStore.isAuthenticated" class="flex flex-wrap gap-2">
        <button
          type="button"
          :class="buttonClass('like')"
          :disabled="loading"
          @click="submitVote('like')"
        >
          Like
        </button>
        <button
          type="button"
          :class="buttonClass('dislike')"
          :disabled="loading"
          @click="submitVote('dislike')"
        >
          Dislike
        </button>
      </div>
    </div>

    <p v-if="!authStore.isAuthenticated" class="mt-3 text-sm text-[var(--color-text-muted)]">
      Log in to vote.
    </p>

    <p v-if="error" class="mt-3 text-sm text-[var(--color-danger)]">
      {{ error }}
    </p>
  </section>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useAuthStore } from '../../../stores/authStore.js'
import { fetchVotes as fetchVotesApi, submitVote as submitVoteApi } from '../../../api/voteApi.js'

const props = defineProps({
  mixId: {
    type: [Number, String],
    required: true,
  },
})

const authStore = useAuthStore()
const votes = ref({
  likes: 0,
  dislikes: 0,
  userVote: null,
})
const loading = ref(false)
const error = ref('')

const fetchVotes = async () => {
  loading.value = true
  error.value = ''

  try {
    votes.value = await fetchVotesApi(props.mixId)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const submitVote = async (voteType) => {
  loading.value = true
  error.value = ''

  try {
    votes.value = await submitVoteApi(props.mixId, voteType)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

const buttonClass = (voteType) => {
  if (votes.value.userVote === voteType) {
    return 'button-primary'
  }

  return 'button-secondary'
}

onMounted(fetchVotes)
watch(() => props.mixId, fetchVotes)
watch(() => authStore.token, fetchVotes)
</script>
