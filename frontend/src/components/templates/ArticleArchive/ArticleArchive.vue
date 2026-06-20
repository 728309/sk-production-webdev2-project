<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header :navigation-links="navigationLinks" />

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
      <div class="mb-8">
        <Heading :level="1" size="3xl" class="mb-2">
          SK Production Hub
        </Heading>
        <Text as="p" size="lg" color="muted">
          Browse curated mixes from producers, DJs, and selectors.
        </Text>
      </div>

      <!-- Mix Grid -->
      <div
        v-if="mixes && mixes.length > 0"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <ArticleCard
          v-for="mix in mixes"
          :key="mix.id"
          :article="mix"
          @click="handleMixClick"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <Text as="p" size="lg" color="muted">
          No mixes found.
        </Text>
      </div>
    </main>

    <Footer
      :quick-links="footerQuickLinks"
      :legal-links="footerLegalLinks"
    />
  </div>
</template>

<script setup>
import Header from '../../organisms/Header/Header.vue';
import Footer from '../../organisms/Footer/Footer.vue';
import ArticleCard from '../../organisms/ArticleCard/ArticleCard.vue';
import Heading from '../../atoms/Heading/Heading.vue';
import Text from '../../atoms/Text/Text.vue';

defineProps({
  mixes: {
    type: Array,
    default: () => [],
  },
  navigationLinks: {
    type: Array,
    default: () => [
      { name: 'Home', href: '/' },
      { name: 'Mixes', href: '/mixes' },
      { name: 'About', href: '/about' },
      { name: 'Contact', href: '/contact' },
    ],
  },
  footerQuickLinks: {
    type: Array,
    default: () => [
      { name: 'Home', href: '/' },
      { name: 'Mixes', href: '/mixes' },
      { name: 'Genres', href: '/genres' },
      { name: 'About', href: '/about' },
    ],
  },
  footerLegalLinks: {
    type: Array,
    default: () => [
      { name: 'Privacy Policy', href: '/privacy' },
      { name: 'Terms of Service', href: '/terms' },
      { name: 'Cookie Policy', href: '/cookies' },
    ],
  },
  showFilters: {
    type: Boolean,
    default: false,
  },
  showPagination: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['mix-click']);

const handleMixClick = (mixId) => {
  emit('mix-click', mixId);
};
</script>
