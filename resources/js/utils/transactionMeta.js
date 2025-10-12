export const getBalanceData = (transactions, balance) => {
  return [
    {
      name: 'Transactions',
      value: transactions.length,
      color: 'primary',
      icon: 'tabler-exchange',
    },
    {
      name: 'Debit',
      value: balance.totalDebit,
      color: 'error',
      icon: 'tabler-arrow-down',
    },
    {
      name: 'Credit',
      value: balance.totalCredit,
      color: 'success',
      icon: 'tabler-arrow-up',
    },
    {
      name: 'Balance',
      value: balance.totalBalance,
      color: 'warning',
      icon: 'tabler-currency-dollar',
    },
  ];
};

export const getTableHeaders = () => {
  return [
    { title: 'User', key: 'user.name' },
    { title: 'Date', key: 'date' },
    { title: 'Description', key: 'description', sortable: false },
    { title: 'Amount', key: 'amount' },
    { title: 'Type', key: 'type.value' },
    { title: 'Category', key: 'category.value' },
    { title: 'Ledger', key: 'ledger.name' },
    { title: 'Actions', key: 'actions', sortable: false },
  ];
};
