import { ofetch } from 'ofetch';
import axios from '@/utils/axios';
import { downloadExcelFile } from './excel';

export const $api = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api',
  async onRequest({ options }) {
    const accessToken = useCookie('accessToken').value;
    if (accessToken)
      options.headers.append('Authorization', `Bearer ${accessToken}`);
  },
});

const axiosCall = async (url, method, data) =>
  await axios({ url, method, data });

export const apiCall = async (
  url,
  method = 'GET',
  data = null,
  responseType = 'json',
) => {
  try {
    if (responseType == 'blob') {
      axios.defaults.responseType = 'blob';
      return downloadExcelFile(await axiosCall(url, method, data));
    }
    const response = await axiosCall(url, method, data);

    return response;
  } catch (error) {
    throw error;
  }
};

export const apiAction = async (apiCall, store, onSuccess = null) => {
  store.hasError = false;
  store.errors = {};

  try {
    const response = await apiCall();
    if (onSuccess) onSuccess(response.data);
    return response;
  } catch (error) {
    store.hasError = true;
    if (error.response && error.response.status === 422) {
      store.errors = error.response.data.errors;
    }
    return error.response;
  }
};
