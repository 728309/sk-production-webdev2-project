<template>
  <RouterLink
    :to="`/mixes/${mix.id}`"
    class="group block h-full overflow-hidden rounded-lg border border-[var(--color-border)] bg-[var(--color-surface)] shadow-[0_18px_40px_rgba(0,0,0,0.35)] transition hover:-translate-y-0.5 hover:border-[var(--color-accent)] hover:shadow-[0_0_24px_rgba(124,255,65,0.16)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)] focus:ring-offset-2 focus:ring-offset-black"
    @click="$emit('click', mix.id)"
  >
    <article class="flex h-full flex-col">
      <div class="flex h-full flex-col p-5 sm:p-6">
        <div class="mb-4 flex items-start justify-between">
          <GenreBadge :genre="mix.genre" />
        </div>

        <Heading :level="3" size="xl" class="mb-4">
          <span class="transition-colors group-hover:text-[var(--color-accent)]">
            {{ mix.title }}
          </span>
        </Heading>

        <Text as="p" size="sm" color="default" weight="semibold" class="mb-3">
          {{ mix.artist }}
        </Text>

        <Text
          as="p"
          size="sm"
          color="muted"
          class="mb-5 min-h-20 line-clamp-3"
        >
          {{ truncatedDescription }}
        </Text>

        <div class="mb-5 grid grid-cols-2 gap-4 text-[0.92rem] leading-6 text-[var(--color-text-soft)]">
          <div>
            <span class="mb-1 block text-[0.78rem] font-semibold uppercase text-[var(--color-text-muted)]">Duration</span>
            <span>{{ mix.duration }}</span>
          </div>
          <div>
            <span class="mb-1 block text-[0.78rem] font-semibold uppercase text-[var(--color-text-muted)]">Platform</span>
            <span>{{ mix.platform }}</span>
          </div>
        </div>

        <div class="mt-auto border-t border-[var(--color-border)] pt-4">
          <MixMeta
            :submitted-by="mix.submittedBy"
            :submitted-date="mix.submittedDate"
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
import MixMeta from '../../molecules/MixMeta/MixMeta.vue';
import GenreBadge from '../../molecules/GenreBadge/GenreBadge.vue';

const props = defineProps({
  mix: {
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
  const description = props.mix.description || '';

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
