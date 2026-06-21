<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <Heading :level="1" size="xl" class="text-blue-600">
            <RouterLink to="/" class="hover:text-blue-700 transition-colors">
              SK Production Hub
            </RouterLink>
          </Heading>
        </div>

        <nav class="hidden md:flex items-center space-x-6">
          <RouterLink
            v-for="link in navigationLinks"
            :key="link.name"
            :to="link.href"
            class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
          >
            {{ link.name }}
          </RouterLink>

          <template v-if="!authStore.isAuthenticated">
            <RouterLink
              to="/login"
              class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            >
              Login
            </RouterLink>
            <RouterLink
              to="/register"
              class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            >
              Register
            </RouterLink>
          </template>

          <template v-else>
            <RouterLink
              to="/submit"
              class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            >
              Submit
            </RouterLink>
            <RouterLink
              to="/my-submissions"
              class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            >
              My Submissions
            </RouterLink>
            <RouterLink
              v-if="authStore.isAdmin"
              to="/admin/pending"
              class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            >
              Admin Pending
            </RouterLink>
            <span
              v-if="authStore.isAdmin"
              class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700"
            >
              Admin
            </span>
            <span class="text-sm text-gray-600">
              {{ authStore.user.name }}
            </span>
            <button
              type="button"
              class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
              @click="handleLogout"
            >
              Logout
            </button>
          </template>
        </nav>

        <div class="md:hidden">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="text-gray-700 hover:text-blue-600 transition-colors"
            aria-label="Toggle menu"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                v-if="!mobileMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-if="mobileMenuOpen" class="md:hidden pb-4">
        <nav class="flex flex-col space-y-2">
          <RouterLink
            v-for="link in navigationLinks"
            :key="link.name"
            :to="link.href"
            class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
            @click="mobileMenuOpen = false"
          >
            {{ link.name }}
          </RouterLink>

          <template v-if="!authStore.isAuthenticated">
            <RouterLink
              to="/login"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="mobileMenuOpen = false"
            >
              Login
            </RouterLink>
            <RouterLink
              to="/register"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="mobileMenuOpen = false"
            >
              Register
            </RouterLink>
          </template>

          <template v-else>
            <RouterLink
              to="/submit"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="mobileMenuOpen = false"
            >
              Submit
            </RouterLink>
            <RouterLink
              to="/my-submissions"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="mobileMenuOpen = false"
            >
              My Submissions
            </RouterLink>
            <RouterLink
              v-if="authStore.isAdmin"
              to="/admin/pending"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="mobileMenuOpen = false"
            >
              Admin Pending
            </RouterLink>
            <span
              v-if="authStore.isAdmin"
              class="mx-2 w-fit rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700"
            >
              Admin
            </span>
            <span class="px-2 py-1 text-sm text-gray-600">
              {{ authStore.user.name }}
            </span>
            <button
              type="button"
              class="px-2 py-1 text-left text-gray-700 hover:text-blue-600 transition-colors"
              @click="handleLogout"
            >
              Logout
            </button>
          </template>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";
import { useAuthStore } from "../../../stores/authStore.js";
import Heading from "../../atoms/Heading/Heading.vue";

defineProps({
  navigationLinks: {
    type: Array,
    default: () => [
      { name: "Home", href: "/" },
      { name: "Mixes", href: "/mixes" },
      { name: "About", href: "/about" },
      { name: "Contact", href: "/contact" },
    ],
  },
});

const authStore = useAuthStore();
const mobileMenuOpen = ref(false);

const handleLogout = () => {
  authStore.logout();
  mobileMenuOpen.value = false;
};
</script>
