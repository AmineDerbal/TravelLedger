import { defineStore } from 'pinia';

export default userStore = defineStore('user', {
  state: () => ({
    user: null,
    hasError: false,
    errors: {},
  }),
  persist: true,

  actions: {
    async login(data) {},
  },
});
