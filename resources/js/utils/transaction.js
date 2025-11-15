export const updateLedgerBalance = async (ledgerStore) => {
  await ledgerStore.UpdateLedgerBalance(ledgerStore.ledger.id);
};

export const storeTransaction = async (
  transactionStore,
  ledgerStore,
  displayToast,
  data,
) => {
  const response = await transactionStore.storeTransaction(data);
  displayToast(201, response.status, response.data.message);

  if (response.status === 201) {
    await updateLedgerBalance(ledgerStore);
  }
};

export const updateTransaction = async (
  transactionStore,
  ledgerStore,
  displayToast,
  rangeDateData,
  data,
) => {
  const response = await transactionStore.updateTransaction(data);
  displayToast(200, response.status, response.data.message);

  if (response.status === 200) {
    await updateLedgerBalance(ledgerStore);
    await transactionStore.getTransactionsByDateRange(rangeDateData);
  }
};

export const deactivateTransaction = async (
  transactionStore,
  ledgerStore,
  displayToast,
  rangeDateData,
  id,
  data,
) => {
  const response = await transactionStore.deactivateTransaction(id, data);
  displayToast(200, response.status, response.data.message);

  if (response.status === 200) {
    await ledgerStore.UpdateLedgerBalance(ledgerStore.ledger.id);
    await transactionStore.getTransactionsByDateRange(rangeDateData);
  }
};
