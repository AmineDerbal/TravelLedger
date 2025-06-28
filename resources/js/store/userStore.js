import { defineStore } from 'pinia';

import { apiCall, apiAction } from '@/utils/api';

export default defineStore('user', {
  state: () => ({
    userData: null,
    isAuthenticated: false,
    hasError: false,
    permissions: [],
    errors: {},
  }),
  persist: ['isAuthenticated', 'userData'],

  actions: {
    clearUserData() {
      this.userData = null;
      this.isAuthenticated = false;
    },
    setIsAuthenticated(value) {
      this.isAuthenticated = value;
    },
    async login(data) {
      return await apiAction(
        () => apiCall('auth/login', 'POST', data),
        this,
        (data) => (this.userData = data),
      );
    },

    async logout(data) {
      const response = await apiAction(
        () => apiCall('auth/logout', 'POST', data),
        this,
      );
      if (response.status === 200) this.clearUserData();
      return response;
    },

    async getUserPermissions(id) {
      await apiAction(() => apiCall(`users/${id}/permissions`), this);
    },
  },
});
