<template>
  <RouterLink
    :to="`/mixes/${article.id}`"
    class="group block h-full overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    @click="$emit('click', article.id)"
  >
    <article class="flex h-full flex-col">
      <div class="flex h-full flex-col p-5 sm:p-6">
        <div class="flex items-start justify-between mb-3">
          <CategoryBadge :genre="article.genre" />
        </div>

        <Heading :level="3" size="xl" class="mb-3">
          <span class="transition-colors group-hover:text-blue-600">
            {{ article.title }}
          </span>
        </Heading>

        <Text as="p" size="sm" color="default" weight="semibold" class="mb-2">
          {{ article.artist }}
        </Text>

        <Text
          as="p"
          size="sm"
          color="muted"
          class="mb-4 min-h-16 line-clamp-3"
        >
          {{ truncatedDescription }}
        </Text>

        <div class="mb-4 grid grid-cols-2 gap-3 text-sm text-gray-600">
          <div>
            <span class="block text-xs font-semibold uppercase text-gray-500">Duration</span>
            <span>{{ article.duration }}</span>
          </div>
          <div>
            <span class="block text-xs font-semibold uppercase text-gray-500">Platform</span>
            <span>{{ article.platform }}</span>
          </div>
        </div>

        <div class="mt-auto border-t border-gray-100 pt-4">
          <ArticleMeta
            :submitted-by="article.submittedBy"
            :submitted-date="article.submittedDate"
          />
        </div>
      </div>
    </article>
  </RouterLink>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import Heading from '../../atoms/Heading/Heading.vue';
import Text from '../../atoms/Text/Text.vue';
import ArticleMeta from '../../molecules/ArticleMeta/ArticleMeta.vue';
import CategoryBadge from '../../molecules/CategoryBadge/CategoryBadge.vue';

const props = defineProps({
  article: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.id && value.title && value.artist && value.genre && value.submittedBy && value.submittedDate;
    },
  },
});

defineEmits(['click']);

const truncatedDescription = computed(() => {
  const maxLength = 150;
  const description = props.article.description || '';

  if (description.length <= maxLength) {
    return description;
  }
  return description.substring(0, maxLength) + '...';
});
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
