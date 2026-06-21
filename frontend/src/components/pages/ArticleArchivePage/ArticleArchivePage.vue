<template>
  <div>
    <!-- Loading State -->
    <div v-if="showFullPageLoading" class="min-h-screen flex items-center justify-center">
      <div class="text-center">
        <div
          class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"
        ></div>
        <p class="text-gray-600">Loading mixes...</p>
      </div>
    </div>

    <!-- Error State -->
    <div
      v-else-if="showFullPageError"
      class="min-h-screen flex items-center justify-center"
    >
      <div class="text-center max-w-md">
        <div class="text-red-600 text-5xl mb-4">!</div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          Error Loading Mixes
        </h2>
        <p class="text-gray-600 mb-4">{{ error }}</p>
        <button
          @click="fetchMixes"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          Try Again
        </button>
      </div>
    </div>

    <!-- Mix Archive Template -->
    <ArticleArchive
      v-else
      :mixes="mixes"
      :genres="genres"
      :search="search"
      :selected-genre="selectedGenre"
      :pagination="pagination"
      :loading="loading"
      :error="error"
      @mix-click="handleMixClick"
      @search-change="handleSearchChange"
      @genre-change="handleGenreChange"
      @page-change="handlePageChange"
    />
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from "vue";
import ArticleArchive from "../../templates/ArticleArchive/ArticleArchive.vue";
import { get } from "../../../utils/api.js";

const mixes = ref([]);
const loading = ref(true);
const error = ref(null);
const page = ref(1);
const limit = ref(6);
const search = ref("");
const selectedGenre = ref("");
const pagination = ref({
  page: 1,
  limit: 6,
  total: 0,
  totalPages: 0,
});

const genres = [
  "Afrobeat",
  "Techno",
  "House",
  "Hip-Hop",
  "Amapiano",
  "R&B",
  "Dancehall",
  "Drum and Bass",
];

const showFullPageLoading = computed(() => loading.value && mixes.value.length === 0);
const showFullPageError = computed(() => error.value && mixes.value.length === 0);

/**
 * Fetch mixes from the API
 */
const fetchMixes = async () => {
  loading.value = true;
  error.value = null;

  const params = new URLSearchParams({
    page: String(page.value),
    limit: String(limit.value),
  });

  if (selectedGenre.value) {
    params.append("genre", selectedGenre.value);
  }

  if (search.value.trim()) {
    params.append("search", search.value.trim());
  }

  try {
    const response = await get(`/mixes?${params.toString()}`);

    if (!response.ok) {
      throw new Error(
        `Failed to fetch mixes: ${response.status} ${response.statusText}`,
      );
    }

    const result = await response.json();
    mixes.value = result.data || [];
    pagination.value = result.pagination || {
      page: page.value,
      limit: limit.value,
      total: mixes.value.length,
      totalPages: 1,
    };
  } catch (err) {
    console.error("Error fetching mixes:", err);
    error.value = err.message || "Failed to load mixes. Please try again later.";
    mixes.value = [];
  } finally {
    loading.value = false;
  }
};

const handleSearchChange = (value) => {
  search.value = value;
  page.value = 1;
  fetchMixes();
};

const handleGenreChange = (value) => {
  selectedGenre.value = value;
  page.value = 1;
  fetchMixes();
};

const handlePageChange = (newPage) => {
  if (newPage < 1 || newPage > pagination.value.totalPages) {
    return;
  }

  page.value = newPage;
  fetchMixes();
};

/**
 * Handle mix click event
 * @param {number} mixId - The ID of the clicked mix
 */
const handleMixClick = (mixId) => {
  // Navigate to mix detail page
  // This can be implemented with Vue Router or your preferred routing solution
  console.log("Mix clicked:", mixId);
  // Example: router.push(`/mixes/${mixId}`);
};

// Fetch mixes when component is mounted
onMounted(() => {
  fetchMixes();
});
</script>
