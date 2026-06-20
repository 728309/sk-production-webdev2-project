import Header from './Header.vue';

export default {
  title: 'Organisms/Header',
  component: Header,
  tags: ['autodocs'],
};

export const Default = {
  args: {
    navigationLinks: [
      { name: 'Home', href: '/' },
      { name: 'Mixes', href: '/mixes' },
      { name: 'About', href: '/about' },
      { name: 'Contact', href: '/contact' },
    ],
  },
};

export const CustomNavigation = {
  args: {
    navigationLinks: [
      { name: 'Mixes', href: '/mixes' },
      { name: 'Genres', href: '/genres' },
      { name: 'Platforms', href: '/platforms' },
    ],
  },
};
