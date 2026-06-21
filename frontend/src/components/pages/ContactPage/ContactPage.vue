<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <section class="panel panel-padding">
        <p class="page-kicker">Contact node</p>
        <Heading :level="1" size="3xl" class="mb-5">
          CONTACT SK HUB
        </Heading>
        <Text as="p" size="lg" color="secondary" class="max-w-3xl">
          Use this page to report issues, suggest improvements, or send feedback about the mix archive.
        </Text>
      </section>

      <section class="content-section grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
        <form class="panel panel-padding space-y-5" novalidate @submit.prevent="handleSubmit">
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label for="contact-name" class="field-label">Name</label>
              <input
                id="contact-name"
                v-model="form.name"
                type="text"
                class="form-input"
                autocomplete="name"
              />
            </div>

            <div>
              <label for="contact-email" class="field-label">Email</label>
              <input
                id="contact-email"
                v-model="form.email"
                type="email"
                class="form-input"
                autocomplete="email"
              />
            </div>
          </div>

          <div>
            <label for="contact-subject" class="field-label">Subject</label>
            <input
              id="contact-subject"
              v-model="form.subject"
              type="text"
              class="form-input"
            />
          </div>

          <div>
            <label for="contact-message" class="field-label">Message</label>
            <textarea
              id="contact-message"
              v-model="form.message"
              rows="6"
              class="form-input resize-y"
            ></textarea>
          </div>

          <div v-if="error" class="form-error" aria-live="polite">
            <Text as="p" size="sm" color="muted" class="text-[var(--color-danger)]">
              {{ error }}
            </Text>
          </div>

          <div v-if="success" class="form-success" aria-live="polite">
            <Text as="p" size="sm" color="primary">
              {{ success }}
            </Text>
          </div>

          <button type="submit" class="button-primary w-full sm:w-auto">
            Send Message
          </button>
        </form>

        <aside class="panel panel-padding self-start">
          <p class="page-kicker">Need help?</p>
          <Heading :level="2" size="xl" class="mb-4">
            Useful reports
          </Heading>
          <Text as="p" size="base" color="muted" class="mb-5">
            Use the form to report broken links, wrong mix information, or account issues.
          </Text>
          <ul class="space-y-3 text-[0.95rem] leading-6 text-[var(--color-text-soft)]">
            <li><span class="terminal-text font-bold">/</span> Broken mix or cover links</li>
            <li><span class="terminal-text font-bold">/</span> Incorrect artist, genre, or platform details</li>
            <li><span class="terminal-text font-bold">/</span> Login, submission, comment, or vote issues</li>
          </ul>
        </aside>
      </section>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const emptyForm = () => ({
  name: '',
  email: '',
  subject: '',
  message: '',
})

const form = reactive(emptyForm())
const error = ref('')
const success = ref('')

const isValidEmail = (value) => {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
}

const handleSubmit = () => {
  error.value = ''
  success.value = ''

  const missingFields = Object.entries(form)
    .filter(([, value]) => !value.trim())
    .map(([field]) => field)

  if (missingFields.length > 0) {
    error.value = 'Please complete all contact fields before sending your message.'
    return
  }

  if (!isValidEmail(form.email.trim())) {
    error.value = 'Please enter a valid email address.'
    return
  }

  success.value = 'Thank you. Your message has been prepared for the SK HUB team. Contact form handling is prepared for future backend integration.'
  Object.assign(form, emptyForm())
}
</script>
