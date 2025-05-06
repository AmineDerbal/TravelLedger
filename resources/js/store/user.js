import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('user', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    hasError: false,
    errors: {},
  }),
  persist: true,

  actions: {
    async login(data) {
      return await apiAction(() => apiCall('auth/login', 'POST', data), this);
    },
    setIsAuthenticated(value) {
      this.isAuthenticated = value;
    },
  },
});
