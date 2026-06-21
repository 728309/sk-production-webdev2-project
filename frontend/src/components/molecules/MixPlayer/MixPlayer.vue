<template>
  <section class="mix-player panel">
    <div class="mix-player__header">
      <div>
        <p class="page-kicker">Player signal</p>
        <h2 class="mix-player__title">{{ title }}</h2>
      </div>
      <span class="terminal-badge">{{ platformLabel }}</span>
    </div>

    <div v-if="embedUrl" class="mix-player__frame-shell">
      <iframe
        :src="embedUrl"
        :title="`${title} player`"
        class="mix-player__frame"
        loading="lazy"
        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>

    <div v-else class="mix-player__fallback">
      <p class="mix-player__fallback-title">Player preview unavailable</p>
      <p class="mix-player__fallback-text">
        Open the mix directly on {{ platformLabel }}.
      </p>
    </div>

    <div class="mix-player__footer">
      <p class="mix-player__note">
        Embedded playback depends on the source platform and public availability.
      </p>
      <a
        v-if="safeMixUrl"
        :href="safeMixUrl"
        target="_blank"
        rel="noreferrer"
        class="button-secondary"
      >
        Open Mix
      </a>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  mixUrl: {
    type: String,
    required: true,
  },
  platform: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    required: true,
  },
})

const parsedUrl = computed(() => {
  try {
    const url = new URL(props.mixUrl)

    if (url.protocol !== 'http:' && url.protocol !== 'https:') {
      return null
    }

    return url
  } catch (error) {
    return null
  }
})

const normalizedPlatform = computed(() => props.platform.trim().toLowerCase())

const detectedPlatform = computed(() => {
  const url = parsedUrl.value
  const host = url?.hostname.replace(/^www\./, '').toLowerCase() || ''

  if (normalizedPlatform.value.includes('soundcloud') || host === 'soundcloud.com') {
    return 'soundcloud'
  }

  if (normalizedPlatform.value.includes('mixcloud') || host === 'mixcloud.com') {
    return 'mixcloud'
  }

  if (
    normalizedPlatform.value.includes('youtube') ||
    host === 'youtube.com' ||
    host === 'youtu.be'
  ) {
    return 'youtube'
  }

  return 'external'
})

const platformLabel = computed(() => {
  const labels = {
    soundcloud: 'SoundCloud',
    mixcloud: 'Mixcloud',
    youtube: 'YouTube',
    external: props.platform || 'External platform',
  }

  return labels[detectedPlatform.value]
})

const safeMixUrl = computed(() => parsedUrl.value?.toString() || '')

const embedUrl = computed(() => {
  const url = parsedUrl.value

  if (!url) {
    return ''
  }

  if (isLikelyPlaceholderUrl(url)) {
    return ''
  }

  if (detectedPlatform.value === 'soundcloud') {
    return `https://w.soundcloud.com/player/?url=${encodeURIComponent(url.toString())}&color=%237CFF41&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false&visual=false`
  }

  if (detectedPlatform.value === 'mixcloud') {
    const feedPath = url.pathname.endsWith('/') ? url.pathname : `${url.pathname}/`
    return `https://www.mixcloud.com/widget/iframe/?hide_cover=1&mini=0&feed=${encodeURIComponent(feedPath)}`
  }

  if (detectedPlatform.value === 'youtube') {
    const videoId = getYouTubeVideoId(url)
    return videoId ? `https://www.youtube.com/embed/${encodeURIComponent(videoId)}` : ''
  }

  return ''
})

const getYouTubeVideoId = (url) => {
  const host = url.hostname.replace(/^www\./, '').toLowerCase()
  let videoId = ''

  if (host === 'youtu.be') {
    videoId = url.pathname.split('/').filter(Boolean)[0] || ''
  }

  if (host === 'youtube.com') {
    videoId = url.searchParams.get('v') || ''
  }

  return /^[A-Za-z0-9_-]{11}$/.test(videoId) ? videoId : ''
}

const isLikelyPlaceholderUrl = (url) => {
  const value = url.toString().toLowerCase()

  return value.includes('skproductionhub') || value.includes('example.com')
}
</script>

<style scoped>
.mix-player {
  margin-bottom: 1.5rem;
  overflow: hidden;
  padding: 1rem;
}

.mix-player__header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1rem;
}

.mix-player__title {
  color: var(--color-text);
  font-size: 1rem;
  font-weight: 800;
  line-height: 1.4;
}

.mix-player__frame-shell {
  overflow: hidden;
  border: 1px solid var(--color-border-strong);
  border-radius: 0.5rem;
  background: var(--color-black);
  box-shadow: 0 0 24px rgba(124, 255, 65, 0.12);
}

.mix-player__frame {
  display: block;
  width: 100%;
  height: 320px;
  border: 0;
}

.mix-player__fallback {
  border: 1px solid var(--color-border);
  border-radius: 0.5rem;
  background: var(--color-surface-2);
  padding: 1.5rem;
}

.mix-player__fallback-title {
  color: var(--color-text);
  font-weight: 800;
  text-transform: uppercase;
}

.mix-player__fallback-text {
  margin-top: 0.5rem;
  color: var(--color-text-muted);
}

.mix-player__footer {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-top: 1rem;
}

.mix-player__note {
  max-width: 34rem;
  color: var(--color-text-muted);
  font-size: 0.9rem;
}

@media (max-width: 640px) {
  .mix-player {
    padding: 0.85rem;
  }

  .mix-player__frame {
    height: 260px;
  }
}
</style>
