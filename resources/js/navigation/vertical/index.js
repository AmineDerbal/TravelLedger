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
    to: { name: 'transaction' },
  },
  {
    title: 'Parameters',
    icon: { icon: 'tabler-settings' },

    children: [
      {
        title: 'Ledger',
        to: { name: 'parameters-ledger' },
        icon: { icon: 'tabler-currency-dollar' },
      },
      {
        title: 'Ledger Category',
        to: { name: 'parameters-ledger-category' },
        icon: { icon: 'tabler-category' },
      },
    ],
  },
];
