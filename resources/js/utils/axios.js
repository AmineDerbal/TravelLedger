import axios from 'axios';
import useUSerStore from '@/store/user';

const configureAxios = () => {
  axios.defaults.withCredentials = true;
  axios.defaults.headers.common['Accept'] = 'application/json';
  axios.defaults.headers.common['Content-Type'] = 'application/json';
  axios.defaults.headers.common['Authorization'] =
    `Bearer ${useCookie('accessToken').value}` || '';
  axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || '/api';

  axios.interceptors.response.use(
    (response) => response,
    async (error) => {
      const statusCode = error.response.status;

      if (statusCode === 401) {
        useUSerStore().clearUserData();
        window.location.href = '/login';
      }

      return Promise.reject(error);
    },
  );

  return axios;
};

const $axios = configureAxios();

export default $axios;
