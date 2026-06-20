import ArticleArchive from './ArticleArchive.vue';

const sampleMixes = [
  {
    id: 1,
    title: 'Lagos Sunset Groove',
    artist: 'DJ Kemi',
    genre: 'Afrobeat',
    platform: 'SoundCloud',
    mixUrl: 'https://soundcloud.com/skproductionhub/lagos-sunset-groove',
    coverImageUrl: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819',
    duration: '42:18',
    submittedBy: 'Sofia Martins',
    submittedDate: '2026-05-04',
    description: 'A warm Afrobeat session with bright percussion, smooth horn lines, and late-evening party energy.',
    status: 'published',
    featured: true,
  },
  {
    id: 2,
    title: 'Warehouse Pulse 132',
    artist: 'Milo Voss',
    genre: 'Techno',
    platform: 'Mixcloud',
    mixUrl: 'https://www.mixcloud.com/skproductionhub/warehouse-pulse-132',
    coverImageUrl: 'https://images.unsplash.com/photo-1571266028243-d220c9c3b04d',
    duration: '58:44',
    submittedBy: 'Noah Vermeer',
    submittedDate: '2026-05-07',
    description: 'A driving techno mix built around hypnotic drums, dark synth stabs, and steady peak-time momentum.',
    status: 'published',
    featured: false,
  },
  {
    id: 3,
    title: 'Rooftop House Session',
    artist: 'Lena Cole',
    genre: 'House',
    platform: 'SoundCloud',
    mixUrl: 'https://soundcloud.com/skproductionhub/rooftop-house-session',
    coverImageUrl: 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a',
    duration: '46:09',
    submittedBy: 'Maya Brooks',
    submittedDate: '2026-05-12',
    description: 'Uplifting house selections with soulful vocals, crisp claps, and a relaxed rooftop feel.',
    status: 'published',
    featured: true,
  },
  {
    id: 4,
    title: 'Late Night Cypher Tape',
    artist: 'Northside Kai',
    genre: 'Hip-Hop',
    platform: 'YouTube',
    mixUrl: 'https://www.youtube.com/watch?v=skproductionhub-cypher',
    coverImageUrl: 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f',
    duration: '35:27',
    submittedBy: 'Jordan Price',
    submittedDate: '2026-05-15',
    description: 'Boom bap drums, mellow keys, and sharp underground verses stitched into a late-night hip-hop blend.',
    status: 'published',
    featured: false,
  },
];

export default {
  title: 'Templates/ArticleArchive',
  component: ArticleArchive,
  tags: ['autodocs'],
  argTypes: {
    mixes: {
      control: 'object',
    },
  },
};

export const Default = {
  args: {
    mixes: sampleMixes,
  },
};

export const Empty = {
  args: {
    mixes: [],
  },
};

export const SingleMix = {
  args: {
    mixes: [sampleMixes[0]],
  },
};

export const ManyMixes = {
  args: {
    mixes: [
      ...sampleMixes,
      {
        id: 5,
        title: 'Pretoria Balcony Keys',
        artist: 'Thabo Nine',
        genre: 'Amapiano',
        platform: 'Mixcloud',
        duration: '51:33',
        submittedBy: 'Aisha Khan',
        submittedDate: '2026-05-19',
        description: 'Amapiano grooves with rolling log drums, airy pads, and smooth weekend balcony energy.',
      },
      {
        id: 6,
        title: 'Velvet Room Slow Jams',
        artist: 'Nia Vale',
        genre: 'R&B',
        platform: 'SoundCloud',
        duration: '39:52',
        submittedBy: 'Elena Rossi',
        submittedDate: '2026-05-22',
        description: 'A smooth R&B set with glossy vocals, soft basslines, and slow grooves for after-hours listening.',
      },
    ],
  },
};
