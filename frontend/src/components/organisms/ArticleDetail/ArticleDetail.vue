<template>
  <article class="max-w-4xl mx-auto">
    <header class="mb-6">
      <div class="mb-4">
        <CategoryBadge :genre="article.genre" />
      </div>

      <Heading :level="1" size="3xl" class="mb-4">
        {{ article.title }}
      </Heading>

      <Text as="p" size="lg" color="default" weight="semibold" class="mb-4">
        {{ article.artist }}
      </Text>

      <ArticleMeta
        :submitted-by="article.submittedBy"
        :submitted-date="article.submittedDate"
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
        {{ article.description }}
      </Text>
    </div>
  </article>
</template>

<script setup>
import Heading from '../../atoms/Heading/Heading.vue';
import Text from '../../atoms/Text/Text.vue';
import ArticleMeta from '../../molecules/ArticleMeta/ArticleMeta.vue';
import CategoryBadge from '../../molecules/CategoryBadge/CategoryBadge.vue';

defineProps({
  article: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.id && value.title && value.artist && value.genre && value.submittedBy && value.submittedDate;
    },
  },
});
</script>

<style scoped>
.prose {
  color: #374151;
}

.prose p {
  margin-bottom: 1.25em;
}
</style>
