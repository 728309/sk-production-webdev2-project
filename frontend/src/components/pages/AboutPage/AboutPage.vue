<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
      <Heading :level="1" size="3xl" class="mb-4">
        About
      </Heading>
      <Text as="p" size="base" color="muted" class="leading-relaxed">
        SK Production Hub is a simple archive for discovering submitted mixes across genres and platforms.
      </Text>
    </main>

    <Footer />
  </div>
</template>

<script setup>
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
</script>
