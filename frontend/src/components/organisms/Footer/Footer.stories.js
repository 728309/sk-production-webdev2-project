import Footer from './Footer.vue';

export default {
  title: 'Organisms/Footer',
  component: Footer,
  tags: ['autodocs'],
};

export const Default = {
  args: {
    quickLinks: [
      { name: 'Home', href: '/' },
      { name: 'Mixes', href: '/mixes' },
      { name: 'About', href: '/about' },
      { name: 'Contact', href: '/contact' },
    ],
    legalLinks: [],
  },
};

export const Minimal = {
  args: {
    quickLinks: [
      { name: 'Home', href: '/' },
      { name: 'Mixes', href: '/mixes' },
    ],
    legalLinks: [],
  },
};
