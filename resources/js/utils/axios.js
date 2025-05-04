import axios from 'axios';

const configureAxios = () => {
  axios.defaults.withCredentials = true;
  axios.defaults.headers.common['Accept'] = 'application/json';
  axios.defaults.headers.common['Content-Type'] = 'application/json';
  axios.defaults.headers.common['Authorization'] =
    `Bearer ${useCookie('accessToken').value}` || '';
  axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || '/api';

  return axios;
};

const $axios = configureAxios();

export default $axios;
