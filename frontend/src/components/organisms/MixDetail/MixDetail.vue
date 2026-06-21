<template>
  <article class="max-w-4xl mx-auto">
    <header class="mb-6">
      <div class="mb-4">
        <GenreBadge :genre="mix.genre" />
      </div>

      <Heading :level="1" size="3xl" class="mb-4">
        {{ mix.title }}
      </Heading>

      <Text as="p" size="lg" color="default" weight="semibold" class="mb-4">
        {{ mix.artist }}
      </Text>

      <MixMeta
        :posted-date="mix.postedDate"
        :submitted-date="mix.submittedDate"
        class="mb-6"
      />
    </header>

    <div class="prose prose-lg max-w-none">
      <Text
        as="div"
        size="base"
        color="default"
        class="whitespace-pre-line leading-relaxed"
      >
        {{ mix.description }}
      </Text>
    </div>
  </article>
</template>

<script setup>
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
import MixMeta from '../../molecules/MixMeta/MixMeta.vue'
import GenreBadge from '../../molecules/GenreBadge/GenreBadge.vue'

defineProps({
  mix: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.id && value.title && value.artist && value.genre
    },
  },
})
</script>

<style scoped>
.prose {
  color: var(--color-text-soft);
}

.prose p {
  margin-bottom: 1.25em;
}
</style>
