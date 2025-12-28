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
    ledgersWithCategories: [],
    hasError: false,
    errors: {},
  }),
  persist: ['ledger', 'ledgers', 'ledgersForSelect', 'ledgersWithCategories'],
  actions: {
    async getLedgers() {
      return await apiAction(
        () => apiCall('ledgers'),
        this,
        (data) => (this.ledgers = data),
      );
    },

    async getLedger(id) {
      return await apiAction(
        () => apiCall(`ledgers/${id}`),
        this,
        (data) => (this.ledger = data),
      );
    },
    async getFirstLedger() {
      return await apiAction(
        () => apiCall('ledgers/first-entry'),
        this,
        (data) => (this.ledger = data),
      );
    },

    async storeLedger(data) {
      return await apiAction(
        () => apiCall('ledgers/store', 'POST', data),
        this,
      );
    },

    async updateLedger(data) {
      const id = data.id;
      return await apiAction(
        () => apiCall(`ledgers/${id}/update`, 'PUT', data),
        this,
      );
    },

    async UpdateLedgerBalance(id) {
      return await apiAction(
        () => apiCall(`ledgers/${id}/balance`),
        this,
        (data) => {
          if (id === data.id && this.ledger.name !== data.name)
            this.ledger.name = data.name;
          this.ledger.balance = data.balance;
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

    async getLedgersWithCategories() {
      return await apiAction(
        () => apiCall('ledgers-with-categories'),
        this,
        (data) => (this.ledgersWithCategories = data),
      );
    },
  },
});
