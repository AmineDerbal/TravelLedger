import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('ledger', {
  state: () => ({
    ledger: {
      id: null,
      name: null,
      amount: null,
    },
    ledgers: [],
    ledgersForSelect: [],
    ledgerCategoryTypes: [],
    ledgerCategories: [],
    hasError: false,
    errors: {},
  }),
  persist: [
    'ledger',
    'ledgers',
    'ledgersForSelect',
    'ledgerCategoryTypes',
    'ledgerCategories',
  ],
  actions: {
    async getLedgers() {
      return await apiAction(
        () => apiCall('ledgers'),
        this,
        (data) => (this.ledgers = data),
      );
    },
    async getFirstLedger() {
      return await apiAction(
        () => apiCall('ledgers/first-entry'),
        this,
        (data) => (this.ledger = data),
      );
    },

    async UpdateLedgerAmount(id) {
      return await apiAction(
        () => apiCall(`ledgers/${id}/amount`),
        this,
        (data) => {
          if (id === data.id && this.ledger.name !== data.name)
            this.ledger.name = data.name;
          this.ledger.amount = Number(data.amount);
        },
      );
    },

    async getLedgersForSelect() {
      return await apiAction(
        () => apiCall('ledgers/for-select'),
        this,
        (data) => (this.ledgersForSelect = data),
      );
    },
  },
});
