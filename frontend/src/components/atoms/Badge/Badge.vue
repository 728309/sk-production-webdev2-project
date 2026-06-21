<template>
  <span :class="classes">
    <slot></slot>
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'primary', 'secondary', 'success', 'warning', 'danger'].includes(value),
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
});

const classes = computed(() => {
  const base = 'inline-flex items-center rounded-full border font-bold uppercase tracking-normal';
  
  const variants = {
    default: 'border-[var(--color-border)] bg-[var(--color-surface-2)] text-[var(--color-text-soft)]',
    primary: 'border-[var(--color-accent)] bg-[rgba(124,255,65,0.08)] text-[var(--color-accent)]',
    secondary: 'border-[var(--color-border-strong)] bg-[rgba(124,255,65,0.04)] text-[var(--color-text-soft)]',
    success: 'border-[var(--color-accent)] bg-[rgba(124,255,65,0.1)] text-[var(--color-accent)]',
    warning: 'border-[var(--color-warning)] bg-[rgba(255,200,87,0.1)] text-[var(--color-warning)]',
    danger: 'border-[var(--color-danger)] bg-[rgba(255,92,92,0.1)] text-[var(--color-danger)]',
  };
  
  const sizes = {
    sm: 'px-2 py-0.5 text-xs',
    md: 'px-3 py-1 text-sm',
    lg: 'px-4 py-1.5 text-base',
  };
  
  return `${base} ${variants[props.variant]} ${sizes[props.size]}`;
});
</script>
