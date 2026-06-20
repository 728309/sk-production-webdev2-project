<template>
  <article class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
    <div class="p-6">
      <div class="flex items-start justify-between mb-3">
        <CategoryBadge :genre="article.genre" />
      </div>

      <Heading :level="3" size="xl" class="mb-3">
        <a
          :href="`#/mixes/${article.id}`"
          class="hover:text-blue-600 transition-colors"
          @click.prevent="$emit('click', article.id)"
        >
          {{ article.title }}
        </a>
      </Heading>

      <Text as="p" size="sm" color="default" weight="semibold" class="mb-2">
        {{ article.artist }}
      </Text>

      <Text
        as="p"
        size="sm"
        color="muted"
        class="mb-4 line-clamp-3"
      >
        {{ truncatedDescription }}
      </Text>

      <div class="grid grid-cols-2 gap-3 mb-4 text-sm text-gray-600">
        <div>
          <span class="block text-xs font-semibold uppercase text-gray-500">Duration</span>
          <span>{{ article.duration }}</span>
        </div>
        <div>
          <span class="block text-xs font-semibold uppercase text-gray-500">Platform</span>
          <span>{{ article.platform }}</span>
        </div>
      </div>

      <ArticleMeta
        :submitted-by="article.submittedBy"
        :submitted-date="article.submittedDate"
      />
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue';
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
