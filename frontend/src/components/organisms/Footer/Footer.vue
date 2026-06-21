<template>
  <footer class="mt-auto border-t border-[var(--color-border)] bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
          <Heading :level="3" size="lg" class="terminal-text mb-4">
            SK HUB
          </Heading>
          <Text as="p" size="sm" color="muted" class="mb-4">
            Underground mixes, curated submissions, and community signals in one dark terminal.
          </Text>
          <div class="flex space-x-4">
            <a
              v-for="social in socialLinks"
              :key="social.name"
              :href="social.href"
              class="text-[var(--color-text-muted)] hover:text-[var(--color-accent)] transition-colors"
              :aria-label="social.name"
            >
              <component :is="social.icon" class="h-5 w-5" />
            </a>
          </div>
        </div>

        <div>
          <Heading :level="4" size="md" class="mb-4 uppercase">
            Quick Links
          </Heading>
          <ul class="space-y-2">
            <li v-for="link in quickLinks" :key="link.name">
              <a
                :href="link.href"
                class="text-sm font-semibold uppercase text-[var(--color-text-muted)] transition-colors hover:text-[var(--color-accent)]"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>

        <div v-if="legalLinks.length > 0">
          <Heading :level="4" size="md" class="mb-4 uppercase">
            Legal
          </Heading>
          <ul class="space-y-2">
            <li v-for="link in legalLinks" :key="link.name">
              <a
                :href="link.href"
                class="text-sm font-semibold uppercase text-[var(--color-text-muted)] transition-colors hover:text-[var(--color-accent)]"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="mt-8 border-t border-[var(--color-border)] pt-8">
        <Text as="p" size="sm" color="muted" class="text-center">
          (c) {{ currentYear }} SK HUB. All rights reserved.
        </Text>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

defineProps({
  quickLinks: {
    type: Array,
    default: () => [
      { name: 'Home', href: '/' },
      { name: 'Mixes', href: '/mixes' },
      { name: 'About', href: '/about' },
      { name: 'Contact', href: '/contact' },
    ],
  },
  legalLinks: {
    type: Array,
    default: () => [],
  },
  socialLinks: {
    type: Array,
    default: () => [],
  },
})

const currentYear = computed(() => new Date().getFullYear())
</script>
