<template>
  <button type="button" :class="classes" @click="onClick" :style="style">
    {{ label }}
  </button>
</template>

<script>
import { computed, reactive } from "vue";

export default {
  name: "my-button",

  props: {
    label: {
      type: String,
      required: true,
    },
    primary: {
      type: Boolean,
      default: false,
    },
    size: {
      type: String,
      validator: function (value) {
        return ["small", "medium", "large"].indexOf(value) !== -1;
      },
    },
    backgroundColor: {
      type: String,
    },
  },

  emits: ["click"],

  setup(props, { emit }) {
    props = reactive(props);
    return {
      classes: computed(() => {
        const baseClasses = "inline-block cursor-pointer rounded-md border font-bold uppercase leading-none transition-colors";
        
        const variantClasses = props.primary
          ? "border-[var(--color-accent)] bg-[var(--color-accent)] text-black"
          : "border-[var(--color-border-strong)] bg-transparent text-[var(--color-accent)]";
        
        const sizeClasses = {
          small: "px-4 py-2.5 text-xs",
          medium: "px-5 py-2.5 text-sm",
          large: "px-6 py-3 text-base",
        };
        
        const size = props.size || "medium";
        
        return `${baseClasses} ${variantClasses} ${sizeClasses[size]}`;
      }),
      style: computed(() => ({
        backgroundColor: props.backgroundColor,
      })),
      onClick() {
        emit("click");
      },
    };
  },
};
</script>
