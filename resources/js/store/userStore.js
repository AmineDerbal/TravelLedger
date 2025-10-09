import { defineStore } from 'pinia';

import { apiCall, apiAction } from '@/utils/api';

export default defineStore('user', {
  state: () => ({
    userData: null,
    users: [],
    isAuthenticated: false,
    hasError: false,
    permissions: [],
    roles: [],
    errors: {},
  }),
  persist: ['isAuthenticated', 'userData'],

  actions: {
    clearUserData() {
      this.userData = null;
      this.isAuthenticated = false;
      this.users = [];
      this.roles = [];
    },
    setIsAuthenticated(value) {
      this.isAuthenticated = value;
    },

    async getUsers() {
      return await apiAction(
        () => apiCall('users'),
        this,
        (data) => (this.users = data),
      );
    },

    async register(data) {
      return await apiAction(
        () => apiCall('auth/register', 'POST', data),
        this,
        (data) => (this.userData = data),
      );
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

    async getUserRoles() {
      await apiAction(
        () => apiCall('users/roles'),
        this,
        (data) => (this.roles = data),
      );
    },
  },
});
