<template>
  <div class="space-y-1 text-sm text-gray-600">
    <Text as="p" size="sm" color="muted">
      <span class="font-semibold text-gray-700">Submitted by</span>
      {{ ' ' }}
      {{ submittedBy }}
    </Text>
    <Text as="p" size="sm" color="muted">
      <span class="font-semibold text-gray-700">Submitted on</span>
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
