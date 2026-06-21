<template>
  <div class="space-y-1 text-sm text-[var(--color-text-muted)]">
    <Text as="p" size="sm" color="muted">
      <span class="font-semibold uppercase text-[var(--color-text-soft)]">Submitted by</span>
      {{ ' ' }}
      {{ submittedBy }}
    </Text>
    <Text as="p" size="sm" color="muted">
      <span class="font-semibold uppercase text-[var(--color-text-soft)]">Submitted on</span>
      {{ ' ' }}
      <DateDisplay :date="submittedDate" format="short" />
    </Text>
  </div>
</template>

<script setup>
import Text from '../../atoms/Text/Text.vue';
import DateDisplay from '../../atoms/DateDisplay/DateDisplay.vue';

defineProps({
  submittedBy: {
    type: String,
    required: true,
  },
  submittedDate: {
    type: String,
    required: true,
  },
});
</script>
