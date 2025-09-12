export default [
  {
    title: 'Home',
    to: { name: 'root' },
    icon: { icon: 'tabler-smart-home' },
    subject: 'Dashboard',
    action: 'view',
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
    subject: 'Transaction',
    action: 'view',
  },
  {
    title: 'Parameters',
    icon: { icon: 'tabler-settings' },

    children: [
      {
        title: 'Ledgers',
        to: { name: 'parameters-ledger' },
        icon: { icon: 'tabler-currency-dollar' },
        subject: 'Ledger',
        action: 'view',
      },
      {
        title: 'Ledger Categories',
        to: { name: 'parameters-ledger-category' },
        icon: { icon: 'tabler-category' },
        subject: 'Ledger',
        action: 'view',
      },
      {
        title: 'users',
        icon: { icon: 'tabler-users' },
        children: [
          {
            title: 'Users list',
            to: { name: 'parameters-user' },
            icon: { icon: 'tabler-users' },
            subject: 'User',
            action: 'manage',
          },
        ],
      },
    ],
  },
];
