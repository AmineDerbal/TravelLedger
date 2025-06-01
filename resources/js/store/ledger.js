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
    hasError: false,
    errors: {},
  }),
  persist: ['ledger'],
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
        (data) => (this.ledger.amount = Number(data)),
      );
    },
  },
});
