import { useToast } from 'vue-toastification';

export const displayToast = (successStatusCode, status, message) => {
  const toast = useToast();
  successStatusCode === status ? toast.success(message) : toast.error(message);
};
