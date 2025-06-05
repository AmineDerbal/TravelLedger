import { get } from '@vueuse/core';

export default defineStore('ledgerCategory', {
  state: () => ({
    ledgerCategories: [],
    LedgerOptions: [],
    typeOptions: [],
    errors: {},
  }),
  persist: ['ledgerCategories', 'LedgerOptions', 'typeOptions'],

  actions: {
    async getLedgerCategories() {
      return await apiAction(
        () => apiCall('ledger-categories'),
        this,
        (data) => (this.ledgerCategories = data),
      );
    },
    async getLedgerCategoryOptions() {
      return await apiAction(
        () => apiCall('ledger-categories/ledger-categories-options'),
        this,
        (data) => {
          this.LedgerOptions = data.ledgerOptions;
          this.typeOptions = data.typeOptions;
        },
      );
    },

    async storeLedgerCategory(data) {
      return await apiAction(
        () => apiCall('ledger-categories/store', 'POST', data),
        this,
      );
    },
  },
});
