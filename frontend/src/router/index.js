import { createRouter, createWebHistory } from 'vue-router'
import ArticleArchivePage from '../components/pages/ArticleArchivePage/ArticleArchivePage.vue'
import LoginPage from '../components/pages/LoginPage/LoginPage.vue'
import RegisterPage from '../components/pages/RegisterPage/RegisterPage.vue'
import AboutPage from '../components/pages/AboutPage/AboutPage.vue'
import ContactPage from '../components/pages/ContactPage/ContactPage.vue'
import SubmitMixPage from '../components/pages/SubmitMixPage/SubmitMixPage.vue'
import MySubmissionsPage from '../components/pages/MySubmissionsPage/MySubmissionsPage.vue'
import AdminPendingPage from '../components/pages/AdminPendingPage/AdminPendingPage.vue'
import { useAuthStore } from '../stores/authStore.js'

const routes = [
  {
    path: '/',
    name: 'home',
    component: ArticleArchivePage,
  },
  {
    path: '/mixes',
    name: 'mixes',
    component: ArticleArchivePage,
  },
  {
    path: '/login',
    name: 'login',
    component: LoginPage,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterPage,
  },
  {
    path: '/about',
    name: 'about',
    component: AboutPage,
  },
  {
    path: '/contact',
    name: 'contact',
    component: ContactPage,
  },
  {
    path: '/submit',
    name: 'submit',
    component: SubmitMixPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/my-submissions',
    name: 'my-submissions',
    component: MySubmissionsPage,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/pending',
    name: 'admin-pending',
    component: AdminPendingPage,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore()

  if (authStore.token && !authStore.user) {
    await authStore.fetchMe()
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return '/login'
  }

  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    return '/mixes'
  }
})

export default router
