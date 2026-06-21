import MixCard from './MixCard.vue'

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
  postedDate: '2026-05-04 00:00:00',
  description: 'A warm Afrobeat session with bright percussion, smooth horn lines, and late-evening party energy.',
  status: 'published',
  featured: true,
}

export default {
  title: 'Organisms/MixCard',
  component: MixCard,
  tags: ['autodocs'],
  argTypes: {
    mix: {
      control: 'object',
    },
  },
}

export const Default = {
  args: {
    mix: sampleMix,
  },
}

export const Techno = {
  args: {
    mix: {
      ...sampleMix,
      id: 2,
      title: 'Warehouse Pulse 132',
      artist: 'Milo Voss',
      genre: 'Techno',
      platform: 'YouTube',
      mixUrl: 'https://www.youtube.com/watch?v=23Oh1LHavuE',
      duration: '58:44',
      submittedBy: 'Noah Vermeer',
      submittedDate: '2026-05-07',
      postedDate: '2026-05-07 00:00:00',
      description: 'A driving techno mix built around hypnotic drums, dark synth stabs, and steady peak-time momentum.',
      featured: false,
    },
  },
}

export const LongDescription = {
  args: {
    mix: {
      ...sampleMix,
      id: 3,
      title: 'Rooftop House Session',
      artist: 'Lena Cole',
      genre: 'House',
      platform: 'YouTube',
      mixUrl: 'https://www.youtube.com/watch?v=mRfwdJx0NDE',
      duration: '46:09',
      submittedBy: 'Maya Brooks',
      submittedDate: '2026-05-12',
      postedDate: '2026-05-12 00:00:00',
      description: 'Uplifting house selections with soulful vocals, crisp claps, and a relaxed rooftop feel that moves from sunny warm-up grooves into deeper late-night club records.',
    },
  },
}

export const Grid = {
  render: () => ({
    components: { MixCard },
    setup() {
      const mixes = [
        sampleMix,
        {
          ...sampleMix,
          id: 2,
          title: 'Warehouse Pulse 132',
          artist: 'Milo Voss',
          genre: 'Techno',
          platform: 'YouTube',
          mixUrl: 'https://www.youtube.com/watch?v=23Oh1LHavuE',
          duration: '58:44',
          submittedBy: 'Noah Vermeer',
          submittedDate: '2026-05-07',
          postedDate: '2026-05-07 00:00:00',
        },
        {
          ...sampleMix,
          id: 3,
          title: 'Pretoria Balcony Keys',
          artist: 'Thabo Nine',
          genre: 'Amapiano',
          platform: 'YouTube',
          mixUrl: 'https://www.youtube.com/watch?v=vy-k0FopsmY',
          duration: '51:33',
          submittedBy: 'Aisha Khan',
          submittedDate: '2026-05-19',
          postedDate: '2026-05-19 00:00:00',
        },
      ]
      return { mixes }
    },
    template: `
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <MixCard v-for="mix in mixes" :key="mix.id" :mix="mix" />
      </div>
    `,
  }),
}
