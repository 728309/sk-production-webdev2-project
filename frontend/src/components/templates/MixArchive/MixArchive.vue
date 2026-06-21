<template>
  <div class="app-page">
    <Header :navigation-links="navigationLinks" />

    <main class="app-container">
      <div class="page-header">
        <p class="page-kicker">Approved signal library</p>
        <Heading :level="1" size="3xl" class="mb-2">
          MIX ARCHIVE
        </Heading>
        <Text as="p" size="lg" color="muted" class="max-w-3xl">
          Browse approved mixes by genre, artist, or platform.
        </Text>
      </div>

      <div class="panel panel-padding mb-6 grid grid-cols-1 gap-4 md:grid-cols-[1fr_220px_auto]">
        <div>
          <label for="mix-search" class="field-label-small">Search mixes</label>
          <input
            id="mix-search"
            :value="search"
            type="search"
            placeholder="Search title, artist, genre, or description"
            class="form-input"
            @input="handleSearchInput"
          />
        </div>

        <div>
          <label for="genre-filter" class="field-label-small">Genre</label>
          <select
            id="genre-filter"
            :value="selectedGenre"
            class="form-input"
            @change="handleGenreChange"
          >
            <option value="">All genres</option>
            <option v-for="genre in genres" :key="genre" :value="genre">
              {{ genre }}
            </option>
          </select>
        </div>

        <div class="flex items-end">
          <button
            type="button"
            class="button-secondary w-full md:w-auto"
            @click="handleReset"
          >
            Reset
          </button>
        </div>
      </div>

      <div v-if="loading" class="state-panel mb-6">
        <Text as="p" size="sm" color="muted">
          Loading mixes...
        </Text>
      </div>

      <div v-if="error" class="form-error mb-6">
        <Text as="p" size="sm" color="muted" class="text-red-700">
          {{ error }}
        </Text>
      </div>

      <!-- Mix Grid -->
      <div
        v-if="mixes && mixes.length > 0"
        class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3"
      >
        <MixCard
          v-for="mix in mixes"
          :key="mix.id"
          :mix="mix"
          @click="handleMixClick"
        />
      </div>

      <!-- Empty State -->
      <div v-else-if="!loading" class="state-panel">
        <Text as="p" size="lg" color="muted">
          No mixes found.
        </Text>
      </div>

      <div
        v-if="pagination.totalPages > 1"
        class="mt-8 flex flex-wrap items-center justify-center gap-3"
      >
        <button
          type="button"
          class="button-secondary"
          :disabled="pagination.page <= 1 || loading"
          @click="handlePageChange(pagination.page - 1)"
        >
          Previous
        </button>

        <Text as="span" size="sm" color="muted">
          Page {{ pagination.page }} of {{ pagination.totalPages }}
        </Text>

        <button
          type="button"
          class="button-secondary"
          :disabled="pagination.page >= pagination.totalPages || loading"
          @click="handlePageChange(pagination.page + 1)"
        >
          Next
        </button>
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
import MixCard from '../../organisms/MixCard/MixCard.vue';
import Heading from '../../atoms/Heading/Heading.vue';
import Text from '../../atoms/Text/Text.vue';

defineProps({
  mixes: {
    type: Array,
    default: () => [],
  },
  genres: {
    type: Array,
    default: () => [],
  },
  search: {
    type: String,
    default: '',
  },
  selectedGenre: {
    type: String,
    default: '',
  },
  pagination: {
    type: Object,
    default: () => ({
      page: 1,
      limit: 6,
      total: 0,
      totalPages: 0,
    }),
  },
  loading: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: null,
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
      { name: 'About', href: '/about' },
      { name: 'Contact', href: '/contact' },
    ],
  },
  footerLegalLinks: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits([
  'mix-click',
  'search-change',
  'genre-change',
  'page-change',
]);

const handleMixClick = (mixId) => {
  emit('mix-click', mixId);
};

const handleSearchInput = (event) => {
  emit('search-change', event.target.value);
};

const handleGenreChange = (event) => {
  emit('genre-change', event.target.value);
};

const handleReset = () => {
  emit('search-change', '');
  emit('genre-change', '');
};

const handlePageChange = (page) => {
  emit('page-change', page);
};
</script>
