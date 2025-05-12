import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('transaction', {
  state: () => ({
    transactions: [],
    hasError: false,
    errors: {},
  }),
  actions: {},
});
