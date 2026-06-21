<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <section class="panel panel-padding">
        <p class="page-kicker">Platform signal</p>
        <Heading :level="1" size="3xl" class="mb-5">
          ABOUT SK HUB
        </Heading>
        <Text as="p" size="lg" color="secondary" class="max-w-3xl">
          SK HUB is a dark digital archive for curated DJ mixes, underground sounds, and community-submitted selections.
        </Text>
      </section>

      <section class="content-section grid grid-cols-1 gap-5 lg:grid-cols-2">
        <article class="content-card">
          <p class="page-kicker">What is SK HUB?</p>
          <Heading :level="2" size="xl" class="mb-4">
            Curated mixes, not random noise
          </Heading>
          <Text as="p" size="base" color="secondary">
            SK HUB helps listeners discover approved mixes from DJs, producers, selectors, and underground artists. The archive is built around music discovery, clear details, and direct access to each mix.
          </Text>
        </article>

        <article class="content-card">
          <p class="page-kicker">Who is it for?</p>
          <Heading :level="2" size="xl" class="mb-4">
            Listeners, artists, and curators
          </Heading>
          <Text as="p" size="base" color="secondary">
            Visitors can browse fresh selections, DJs and producers can submit their work, and admins can review, approve, feature, or manage entries so the archive stays focused.
          </Text>
        </article>
      </section>

      <section class="content-section">
        <div class="mb-6">
          <p class="page-kicker">How it works</p>
          <Heading :level="2" size="2xl" class="mb-3">
            Three steps from signal to archive
          </Heading>
          <Text as="p" size="base" color="muted" class="max-w-3xl">
            The platform keeps discovery public while keeping submissions curated through a simple review flow.
          </Text>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
          <article
            v-for="step in steps"
            :key="step.title"
            class="content-card"
          >
            <span class="terminal-badge mb-4">{{ step.number }}</span>
            <Heading :level="3" size="lg" class="mb-3">
              {{ step.title }}
            </Heading>
            <Text as="p" size="sm" color="muted">
              {{ step.description }}
            </Text>
          </article>
        </div>
      </section>

      <section class="content-section panel panel-padding">
        <p class="page-kicker">Community features</p>
        <Heading :level="2" size="2xl" class="mb-4">
          More than a list of links
        </Heading>
        <Text as="p" size="base" color="secondary" class="max-w-3xl">
          Every mix detail page gives listeners space to read the description, open the original platform link, vote with likes or dislikes, and leave listener notes. Those small signals help the archive feel active without losing its curated focus.
        </Text>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div
            v-for="feature in features"
            :key="feature"
            class="rounded-md border border-[var(--color-border)] bg-[var(--color-surface-2)] p-4 text-[0.95rem] font-semibold text-[var(--color-text-soft)]"
          >
            <span class="terminal-text mr-2">/</span>{{ feature }}
          </div>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const steps = [
  {
    number: '01',
    title: 'Discover approved mixes',
    description: 'Browse the public archive by genre, artist, platform, or search terms.',
  },
  {
    number: '02',
    title: 'Submit your own mix',
    description: 'Logged-in users can send in a mix link, cover image, metadata, and description.',
  },
  {
    number: '03',
    title: 'Admin review keeps it curated',
    description: 'Curators check pending submissions before they appear in the public archive.',
  },
]

const features = [
  'Mix detail pages',
  'Comments and listener notes',
  'Likes and dislikes',
]
</script>
