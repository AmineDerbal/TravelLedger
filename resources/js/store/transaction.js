import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('transaction', {
  state: () => ({
    transactions: [],
    categories: [],
    types: [],
    balance: {
      totalDebit: 0,
      totalCredit: 0,
      totalBalance: 0,
    },
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

    async storeTransaction(data) {
      return await apiAction(
        () => apiCall('transactions/store', 'POST', data),
        this,
      );
    },

    async getTransactionsByDateRange(data) {
      return await apiAction(
        () => apiCall('transactions/date-range', 'POST', data),
        this,
        (data) => {
          this.transactions = data.transactions;
          this.balance = data.balance;
        },
      );
    },

    async updateTransaction(data) {
      return await apiAction(
        () => apiCall('transactions/update', 'PUT', data),
        this,
      );
    },

    async deleteTransaction(id) {
      return await apiAction(
        () => apiCall(`transactions/${id}`, 'DELETE'),
        this,
      );
    },

    async downloadExcelTransactions(data) {
      return await apiAction(async () => {
        const response = await apiCall(
          'export-transactions',
          'POST',
          data,
          'blob',
        );
        const blob = new Blob([response.data], {
          type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        });

        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'transactions.xlsx');
        document.body.appendChild(link);
        link.click();
        URL.revokeObjectURL(link.href);
        return response;
      }, this);
    },
  },
});
