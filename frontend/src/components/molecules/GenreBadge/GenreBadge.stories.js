import GenreBadge from './GenreBadge.vue';

export default {
  title: 'Molecules/GenreBadge',
  component: GenreBadge,
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
    components: { GenreBadge },
    template: `
      <div class="flex gap-2 flex-wrap">
        <GenreBadge genre="Afrobeat" />
        <GenreBadge genre="Techno" />
        <GenreBadge genre="House" />
        <GenreBadge genre="Hip-Hop" />
        <GenreBadge genre="Amapiano" />
        <GenreBadge :genre="'R&B'" />
        <GenreBadge genre="Dancehall" />
        <GenreBadge genre="Drum and Bass" />
      </div>
    `,
  }),
};
