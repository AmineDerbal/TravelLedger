export default [
  {
    title: 'Home',
    to: { name: 'root' },
    icon: { icon: 'tabler-smart-home' },
  },
  {
    title: 'Second page',
    to: { name: 'second-page' },
    icon: { icon: 'tabler-file' },
  },
  {
    title: 'Transactions',
    icon: { icon: 'tabler-transaction-euro' },
    children: [
      {
        title: 'List',
        to: { name: 'transaction' },
      },
    ],
  },
];
