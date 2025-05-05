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

export const apiCall = async (url, method = 'GET', data = null) => {
  const baseUrl = import.meta.env.VITE_API_BASE_URL || '/api';
  const newUrl = `${baseUrl}/${url}`;
  try {
    const response = await axios({ url: newUrl, method, data });
    return response;
  } catch (error) {
    throw error;
  }
};

export const apiAction = async (apiCall, store, onSuccess = null) => {};
