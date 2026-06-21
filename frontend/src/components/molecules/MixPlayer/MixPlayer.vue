<template>
  <section class="mix-player" aria-label="Mix player">
    <div v-if="embedUrl" class="mix-player__frame-wrap">
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
      <p class="mix-player__eyebrow">Player preview unavailable</p>
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
        class="mix-player__link"
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
    default: '',
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
    return ['http:', 'https:'].includes(url.protocol) ? url : null
  } catch (error) {
    return null
  }
})

const normalizedPlatform = computed(() => {
  const platform = props.platform.toLowerCase()
  const hostname = parsedUrl.value?.hostname.replace(/^www\./, '').toLowerCase() || ''

  if (platform.includes('soundcloud') || hostname === 'soundcloud.com') {
    return 'soundcloud'
  }

  if (platform.includes('mixcloud') || hostname === 'mixcloud.com') {
    return 'mixcloud'
  }

  if (
    platform.includes('youtube') ||
    hostname === 'youtube.com' ||
    hostname === 'm.youtube.com' ||
    hostname === 'youtu.be'
  ) {
    return 'youtube'
  }

  return 'external'
})

const platformLabel = computed(() => {
  if (normalizedPlatform.value === 'soundcloud') {
    return 'SoundCloud'
  }

  if (normalizedPlatform.value === 'mixcloud') {
    return 'Mixcloud'
  }

  if (normalizedPlatform.value === 'youtube') {
    return 'YouTube'
  }

  return props.platform || 'the source platform'
})

const safeMixUrl = computed(() => parsedUrl.value?.toString() || '')

const embedUrl = computed(() => {
  const url = parsedUrl.value

  if (!url || isLikelyPlaceholderUrl(url)) {
    return ''
  }

  if (normalizedPlatform.value === 'soundcloud') {
    return `https://w.soundcloud.com/player/?url=${encodeURIComponent(url.toString())}&color=%237CFF41&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=false&visual=false`
  }

  if (normalizedPlatform.value === 'mixcloud') {
    const feedPath = url.pathname.endsWith('/') ? url.pathname : `${url.pathname}/`
    return `https://www.mixcloud.com/widget/iframe/?hide_cover=1&mini=0&feed=${encodeURIComponent(feedPath)}`
  }

  if (normalizedPlatform.value === 'youtube') {
    const videoId = getYouTubeVideoId(url)
    return videoId ? `https://www.youtube.com/embed/${videoId}` : ''
  }

  return ''
})

function getYouTubeVideoId(url) {
  const hostname = url.hostname.replace(/^www\./, '').toLowerCase()
  const id = hostname === 'youtu.be'
    ? url.pathname.split('/').filter(Boolean)[0]
    : url.searchParams.get('v')

  return /^[A-Za-z0-9_-]{11}$/.test(id || '') ? id : ''
}

function isLikelyPlaceholderUrl(url) {
  const value = url.toString().toLowerCase()
  return value.includes('skproductionhub') || value.includes('example.com')
}
</script>

<style scoped>
.mix-player {
  overflow: hidden;
  border: 1px solid var(--color-border-strong);
  border-radius: 0.5rem;
  background:
    linear-gradient(180deg, rgba(124, 255, 65, 0.08), rgba(0, 0, 0, 0)),
    var(--color-surface);
  box-shadow: 0 0 24px rgba(124, 255, 65, 0.12);
}

.mix-player__frame-wrap {
  overflow: hidden;
  background: #000;
}

.mix-player__frame {
  display: block;
  width: 100%;
  height: 320px;
  border: 0;
}

.mix-player__fallback {
  min-height: 220px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 0.75rem;
  padding: 2rem;
  background:
    repeating-linear-gradient(
      0deg,
      rgba(124, 255, 65, 0.04),
      rgba(124, 255, 65, 0.04) 1px,
      transparent 1px,
      transparent 12px
    ),
    #050705;
}

.mix-player__eyebrow {
  color: var(--color-accent);
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 0;
  text-transform: uppercase;
}

.mix-player__fallback-text,
.mix-player__note {
  color: var(--color-text-soft);
  line-height: 1.65;
}

.mix-player__footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1rem 1.25rem;
  border-top: 1px solid var(--color-border);
}

.mix-player__note {
  margin: 0;
  font-size: 0.9rem;
}

.mix-player__link {
  flex-shrink: 0;
  color: #050705;
  background: var(--color-accent);
  border: 1px solid var(--color-accent-bright);
  border-radius: 0.375rem;
  padding: 0.65rem 0.9rem;
  font-size: 0.82rem;
  font-weight: 800;
  text-transform: uppercase;
  box-shadow: 0 0 14px rgba(124, 255, 65, 0.2);
}

.mix-player__link:hover,
.mix-player__link:focus {
  background: var(--color-accent-bright);
  outline: none;
}

@media (max-width: 640px) {
  .mix-player__frame {
    height: 240px;
  }

  .mix-player__footer {
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>
