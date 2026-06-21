import { createRouter, createWebHistory } from 'vue-router'
import ArticleArchivePage from '../components/pages/ArticleArchivePage/ArticleArchivePage.vue'
import LoginPage from '../components/pages/LoginPage/LoginPage.vue'
import RegisterPage from '../components/pages/RegisterPage/RegisterPage.vue'
import AboutPage from '../components/pages/AboutPage/AboutPage.vue'
import ContactPage from '../components/pages/ContactPage/ContactPage.vue'

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
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
