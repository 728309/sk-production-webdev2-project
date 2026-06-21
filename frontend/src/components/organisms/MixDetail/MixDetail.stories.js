import MixDetail from './MixDetail.vue';

const sampleMix = {
  id: 1,
  title: 'Lagos Sunset Groove',
  artist: 'DJ Kemi',
  genre: 'Afrobeat',
  platform: 'YouTube',
  mixUrl: 'https://www.youtube.com/watch?v=bk6Xst6euQk',
  coverImageUrl: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819',
  duration: '42:18',
  submittedBy: 'Sofia Martins',
  submittedDate: '2026-05-04',
  description: 'A warm Afrobeat session with bright percussion, smooth horn lines, and late-evening party energy.',
  status: 'published',
  featured: true,
};

export default {
  title: 'Organisms/MixDetail',
  component: MixDetail,
  tags: ['autodocs'],
  argTypes: {
    mix: {
      control: 'object',
    },
  },
};

export const Default = {
  args: {
    mix: sampleMix,
  },
};

export const Techno = {
  args: {
    mix: {
      ...sampleMix,
      id: 2,
      title: 'Warehouse Pulse 132',
      artist: 'Milo Voss',
      genre: 'Techno',
      platform: 'Mixcloud',
      duration: '58:44',
      submittedBy: 'Noah Vermeer',
      submittedDate: '2026-05-07',
      description: 'A driving techno mix built around hypnotic drums, dark synth stabs, and steady peak-time momentum.',
      featured: false,
    },
  },
};

export const RnB = {
  args: {
    mix: {
      ...sampleMix,
      id: 6,
      title: 'Velvet Room Slow Jams',
      artist: 'Nia Vale',
      genre: 'R&B',
      platform: 'SoundCloud',
      duration: '39:52',
      submittedBy: 'Elena Rossi',
      submittedDate: '2026-05-22',
      description: 'A smooth R&B set with glossy vocals, soft basslines, and slow grooves for after-hours listening.',
      featured: false,
    },
  },
};
