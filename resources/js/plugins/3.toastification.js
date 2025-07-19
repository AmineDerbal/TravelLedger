import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const options = {
  // You can set your default options here
  position: 'top-right',
  timeout: 5000,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
};

export default function (app) {
  app.use(Toast, options);
}
