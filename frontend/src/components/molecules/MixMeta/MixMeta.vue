<template>
  <div v-if="displayDate" class="space-y-1 text-sm text-[var(--color-text-muted)]">
    <Text as="p" size="sm" color="muted">
      <span class="font-semibold uppercase text-[var(--color-text-soft)]">Posted on</span>
      {{ ' ' }}
      <DateDisplay :date="displayDate" format="short" />
    </Text>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import Text from '../../atoms/Text/Text.vue';
import DateDisplay from '../../atoms/DateDisplay/DateDisplay.vue';

const props = defineProps({
  postedDate: {
    type: String,
    default: '',
  },
  submittedBy: {
    type: String,
    default: '',
  },
  submittedDate: {
    type: String,
    default: '',
  },
});

const displayDate = computed(() => props.postedDate || props.submittedDate);
</script>
