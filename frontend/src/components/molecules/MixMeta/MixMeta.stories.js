import MixMeta from './MixMeta.vue'

export default {
  title: 'Molecules/MixMeta',
  component: MixMeta,
  tags: ['autodocs'],
}

export const Default = {
  args: {
    postedDate: '2026-05-04',
  },
}

export const Recent = {
  args: {
    postedDate: new Date().toISOString().split('T')[0],
  },
}

export const OlderPost = {
  args: {
    postedDate: '2026-05-18',
  },
}
