import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('ledger', {
  state: () => ({
    ledger: null,
    ledgers: [],
    hasError: false,
    errors: {},
  }),
  persist: ['ledger'],
  actions: {
    async getFirstLedger() {
      return await apiAction(
        () => apiCall('ledgers/first-entry'),
        this,
        (data) => (this.ledger = data),
      );
    },
  },
});
