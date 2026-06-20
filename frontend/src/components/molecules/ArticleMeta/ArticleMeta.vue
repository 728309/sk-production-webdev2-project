<template>
  <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
    <Text as="span" size="sm" color="muted">
      <span class="font-semibold text-gray-700">Submitted by</span>
      {{ submittedBy }}
    </Text>
    <span class="text-gray-400">|</span>
    <Text as="span" size="sm" color="muted">
      <span class="font-semibold text-gray-700">Submitted date</span>
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
