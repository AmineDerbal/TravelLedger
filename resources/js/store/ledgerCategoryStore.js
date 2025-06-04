import { store } from '@/plugins/2.pinia';

export default defineStore('ledgerCategory', {
  state: () => ({
    ledgerCategories: [],
    LedgerOptions: [],
    typeOptions: [],
  }),
  persist: ['ledgerCategories', 'LedgerOptions', 'typeOptions'],

  actions: {
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
        () => apiCall('ledger-categories', 'POST', data),
        this,
      );
    },
  },
});
