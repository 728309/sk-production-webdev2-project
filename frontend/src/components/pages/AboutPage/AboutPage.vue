<template>
  <div class="app-page">
    <Header />

    <main class="app-container-medium">
      <section class="panel panel-padding">
        <Heading :level="1" size="3xl" class="mb-4">
          About
        </Heading>
        <Text as="p" size="base" color="muted" class="leading-relaxed">
          SK Production Hub is a simple archive for discovering submitted mixes across genres and platforms.
        </Text>
      </section>
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
