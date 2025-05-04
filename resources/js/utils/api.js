import { ofetch } from 'ofetch';
import axios from '@/utils/axios';

export const $api = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  async onRequest({ options }) {
    const accessToken = useCookie('accessToken').value;
    if (accessToken)
      options.headers.append('Authorization', `Bearer ${accessToken}`);
  },
});

export const apiRequest = async (options) => {};
