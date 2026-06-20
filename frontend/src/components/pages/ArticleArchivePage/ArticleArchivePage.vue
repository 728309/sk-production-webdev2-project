<template>
  <div>
    <!-- Loading State -->
    <div v-if="loading" class="min-h-screen flex items-center justify-center">
      <div class="text-center">
        <div
          class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"
        ></div>
        <p class="text-gray-600">Loading mixes...</p>
      </div>
    </div>

    <!-- Error State -->
    <div
      v-else-if="error"
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
      @mix-click="handleMixClick"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ArticleArchive from "../../templates/ArticleArchive/ArticleArchive.vue";
import { get } from "../../../utils/api.js";

const mixes = ref([]);
const loading = ref(true);
const error = ref(null);

/**
 * Fetch mixes from the API
 */
const fetchMixes = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await get("/mixes");

    if (!response.ok) {
      throw new Error(
        `Failed to fetch mixes: ${response.status} ${response.statusText}`,
      );
    }

    const data = await response.json();
    mixes.value = data;
  } catch (err) {
    console.error("Error fetching mixes:", err);
    error.value = err.message || "Failed to load mixes. Please try again later.";
    mixes.value = [];
  } finally {
    loading.value = false;
  }
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
