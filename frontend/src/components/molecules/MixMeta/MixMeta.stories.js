import MixMeta from './MixMeta.vue';

export default {
  title: 'Molecules/MixMeta',
  component: MixMeta,
  tags: ['autodocs'],
};

export const Default = {
  args: {
    submittedBy: 'Sofia Martins',
    submittedDate: '2026-05-04',
  },
};

export const Recent = {
  args: {
    submittedBy: 'Noah Vermeer',
    submittedDate: new Date().toISOString().split('T')[0],
  },
};

export const LongSubmitterName = {
  args: {
    submittedBy: 'Alexandra van den Berg',
    submittedDate: '2026-05-18',
  },
};
