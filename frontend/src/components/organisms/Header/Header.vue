<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex flex-shrink-0 items-center">
          <Heading :level="1" size="xl" class="text-blue-600">
            <RouterLink to="/" class="hover:text-blue-700 transition-colors">
              SK Production Hub
            </RouterLink>
          </Heading>
        </div>

        <nav class="hidden md:flex items-center gap-5 lg:gap-6">
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
            <div ref="accountMenuRef" class="relative">
              <button
                type="button"
                class="flex items-center gap-1 text-gray-700 hover:text-blue-600 transition-colors font-medium"
                @click.stop="toggleAccountMenu"
              >
                My Account
                <span class="text-xs" aria-hidden="true">v</span>
              </button>

              <div
                v-if="accountMenuOpen"
                class="absolute right-0 z-50 mt-3 w-60 overflow-hidden rounded-md border border-gray-200 bg-white shadow-lg"
              >
                <div class="border-b border-gray-100 px-4 py-3">
                  <p class="text-xs font-semibold uppercase text-gray-500">Signed in as</p>
                  <p class="truncate text-sm font-semibold text-gray-900">
                    {{ authStore.user.name }}
                  </p>
                  <p class="mt-1 text-xs font-medium text-gray-600">
                    Role: {{ accountRoleLabel }}
                  </p>
                </div>
                <RouterLink
                  to="/submit"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                  @click="closeMenus"
                >
                  Submit Mix
                </RouterLink>
                <RouterLink
                  to="/my-submissions"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                  @click="closeMenus"
                >
                  My Submissions
                </RouterLink>
                <div v-if="authStore.isAdmin" class="border-t border-gray-100 py-2">
                  <p class="px-4 pb-1 text-xs font-semibold uppercase text-gray-500">
                    Admin
                  </p>
                  <RouterLink
                    to="/admin/pending"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                    @click="closeMenus"
                  >
                    Pending Mixes
                  </RouterLink>
                  <RouterLink
                    to="/admin/mixes"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                    @click="closeMenus"
                  >
                    Manage Mixes
                  </RouterLink>
                </div>
                <button
                  type="button"
                  class="block w-full border-t border-gray-100 px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600"
                  @click="handleLogout"
                >
                  Logout
                </button>
              </div>
            </div>
          </template>
        </nav>

        <div class="md:hidden">
          <button
            @click="toggleMobileMenu"
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
            @click="closeMobileMenu"
          >
            {{ link.name }}
          </RouterLink>

          <template v-if="!authStore.isAuthenticated">
            <RouterLink
              to="/login"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="closeMobileMenu"
            >
              Login
            </RouterLink>
            <RouterLink
              to="/register"
              class="text-gray-700 hover:text-blue-600 transition-colors px-2 py-1"
              @click="closeMobileMenu"
            >
              Register
            </RouterLink>
          </template>

          <template v-else>
            <button
              type="button"
              class="flex items-center justify-between rounded-md px-2 py-2 text-left font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600"
              @click="mobileAccountOpen = !mobileAccountOpen"
            >
              My Account
              <span class="text-xs" aria-hidden="true">v</span>
            </button>

            <div v-if="mobileAccountOpen" class="rounded-md bg-gray-50 py-2">
              <div class="px-4 pb-2">
                <p class="text-xs font-semibold uppercase text-gray-500">Signed in as</p>
                <p class="truncate text-sm font-semibold text-gray-900">
                  {{ authStore.user.name }}
                </p>
                <p class="mt-1 text-xs font-medium text-gray-600">
                  Role: {{ accountRoleLabel }}
                </p>
              </div>
              <RouterLink
                to="/submit"
                class="block px-4 py-2 text-sm text-gray-700 hover:text-blue-600"
                @click="closeMobileMenu"
              >
                Submit Mix
              </RouterLink>
              <RouterLink
                to="/my-submissions"
                class="block px-4 py-2 text-sm text-gray-700 hover:text-blue-600"
                @click="closeMobileMenu"
              >
                My Submissions
              </RouterLink>
              <div v-if="authStore.isAdmin" class="mt-2 border-t border-gray-200 pt-2">
                <p class="px-4 pb-1 text-xs font-semibold uppercase text-gray-500">
                  Admin
                </p>
                <RouterLink
                  to="/admin/pending"
                  class="block px-4 py-2 text-sm text-gray-700 hover:text-blue-600"
                  @click="closeMobileMenu"
                >
                  Pending Mixes
                </RouterLink>
                <RouterLink
                  to="/admin/mixes"
                  class="block px-4 py-2 text-sm text-gray-700 hover:text-blue-600"
                  @click="closeMobileMenu"
                >
                  Manage Mixes
                </RouterLink>
              </div>
              <button
                type="button"
                class="mt-2 block w-full border-t border-gray-200 px-4 py-2 pt-3 text-left text-sm text-gray-700 hover:text-blue-600"
                @click="handleLogout"
              >
                Logout
              </button>
            </div>
          </template>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from "vue";
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
const accountMenuOpen = ref(false);
const mobileAccountOpen = ref(false);
const accountMenuRef = ref(null);

const accountRoleLabel = computed(() => {
  return authStore.isAdmin ? "Admin" : "User";
});

const closeMenus = () => {
  accountMenuOpen.value = false;
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
  mobileAccountOpen.value = false;
};

const toggleMobileMenu = () => {
  if (mobileMenuOpen.value) {
    closeMobileMenu();
    return;
  }

  mobileMenuOpen.value = true;
};

const toggleAccountMenu = () => {
  accountMenuOpen.value = !accountMenuOpen.value;
};

const handleLogout = () => {
  authStore.logout();
  closeMenus();
  closeMobileMenu();
};

const handleDocumentClick = (event) => {
  if (accountMenuRef.value && !accountMenuRef.value.contains(event.target)) {
    accountMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleDocumentClick);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleDocumentClick);
});
</script>
