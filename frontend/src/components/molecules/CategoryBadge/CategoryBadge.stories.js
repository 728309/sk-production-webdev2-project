import CategoryBadge from './CategoryBadge.vue';

export default {
  title: 'Molecules/CategoryBadge',
  component: CategoryBadge,
  tags: ['autodocs'],
};

export const Afrobeat = {
  args: {
    genre: 'Afrobeat',
  },
};

export const Techno = {
  args: {
    genre: 'Techno',
  },
};

export const House = {
  args: {
    genre: 'House',
  },
};

export const AllGenres = {
  render: () => ({
    components: { CategoryBadge },
    template: `
      <div class="flex gap-2 flex-wrap">
        <CategoryBadge genre="Afrobeat" />
        <CategoryBadge genre="Techno" />
        <CategoryBadge genre="House" />
        <CategoryBadge genre="Hip-Hop" />
        <CategoryBadge genre="Amapiano" />
        <CategoryBadge :genre="'R&B'" />
        <CategoryBadge genre="Dancehall" />
        <CategoryBadge genre="Drum and Bass" />
      </div>
    `,
  }),
};
