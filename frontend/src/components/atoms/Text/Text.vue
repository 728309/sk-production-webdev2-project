<template>
  <component :is="tag" :class="classes">
    <slot></slot>
  </component>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  as: {
    type: String,
    default: 'p',
    validator: (value) => ['p', 'span', 'div', 'label'].includes(value),
  },
  size: {
    type: String,
    default: 'base',
    validator: (value) => ['xs', 'sm', 'base', 'lg'].includes(value),
  },
  weight: {
    type: String,
    default: 'normal',
    validator: (value) => ['normal', 'medium', 'semibold', 'bold'].includes(value),
  },
  color: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'muted', 'primary', 'secondary'].includes(value),
  },
});

const tag = computed(() => props.as);

const classes = computed(() => {
  const sizes = {
    xs: 'text-[0.82rem] leading-5',
    sm: 'text-[0.95rem] leading-6',
    base: 'text-base leading-7',
    lg: 'text-lg leading-8',
  };
  
  const weights = {
    normal: 'font-normal',
    medium: 'font-medium',
    semibold: 'font-semibold',
    bold: 'font-bold',
  };
  
  const colors = {
    default: 'text-[var(--color-text)]',
    muted: 'text-[var(--color-text-muted)]',
    primary: 'text-[var(--color-accent)]',
    secondary: 'text-[var(--color-text-soft)]',
  };
  
  return `${sizes[props.size]} ${weights[props.weight]} ${colors[props.color]}`;
});
</script>
