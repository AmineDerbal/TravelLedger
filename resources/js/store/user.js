import { defineStore } from 'pinia';
import { apiCall, apiAction } from '@/utils/api';

export default defineStore('user', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    hasError: false,
    errors: {},
  }),
  persist: ['isAuthenticated'],

  actions: {
    setIsAuthenticated(value) {
      this.isAuthenticated = value;
    },
    async login(data) {
      return await apiAction(() => apiCall('auth/login', 'POST', data), this);
    },

    async logout(data) {
      const response = await apiAction(
        () => apiCall('auth/logout', 'POST', data),
        this,
      );
      if (response.status === 200) this.setIsAuthenticated(false);
      return response;
    },
  },
});
