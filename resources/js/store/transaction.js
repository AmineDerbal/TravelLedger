import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('transaction', {
  state: () => ({
    transactions: [],
    categories: [],
    types: [],
    hasError: false,
    errors: {},
  }),
  actions: {
    async getTransactionCategories() {
      return await apiAction(
        () => apiCall('transaction-categories'),
        this,
        (data) => (this.categories = data),
      );
    },
    async getTransactionTypes() {
      return await apiAction(
        () => apiCall('transaction-types'),
        this,
        (data) => (this.types = data),
      );
    },
  },
});
