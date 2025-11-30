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

    async updateLedgerCategory(data) {
      const id = data.id;
      return await apiAction(
        () => apiCall(`ledger-categories/update/${id}`, 'PUT', data),
        this,
      );
    },

    async deleteLedgerCategory(id) {
      return await apiAction(
        () => apiCall(`ledger-categories/${id}`, 'DELETE'),
        this,
      );
    },
  },
});
