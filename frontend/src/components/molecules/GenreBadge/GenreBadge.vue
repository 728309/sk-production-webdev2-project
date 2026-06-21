<template>
  <Badge :variant="genreVariant" size="sm">
    {{ genre }}
  </Badge>
</template>

<script setup>
import { computed } from 'vue'
import Badge from '../../atoms/Badge/Badge.vue'

const props = defineProps({
  genre: {
    type: String,
    required: true,
  },
})

const genreVariant = computed(() => {
  const variantMap = {
    'Afrobeat': 'success',
    'Techno': 'primary',
    'House': 'warning',
    'Hip-Hop': 'secondary',
    'Amapiano': 'success',
    'R&B': 'danger',
    'Dancehall': 'warning',
    'Drum and Bass': 'default',
  }

  return variantMap[props.genre] || 'default'
})
</script>
