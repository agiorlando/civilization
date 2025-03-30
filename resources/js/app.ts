import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

declare global {
  interface Window {
    Echo: Echo<any>;
    Pusher: typeof Pusher;
  }
}

window.Pusher = Pusher;

const scheme = import.meta.env.VITE_REVERB_SCHEME || 'http';

const echo = new Echo<any>({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY || 'local',
  cluster: import.meta.env.VITE_REVERB_APP_CLUSTER || 'mt1',
  forceTLS: scheme === 'https',
  encrypted: scheme === 'https',
  enabledTransports: ['ws', 'wss'],
  wsHost: import.meta.env.VITE_REVERB_HOST || 'host.docker.internal',
  wsPort: Number(import.meta.env.VITE_REVERB_PORT) || 6001,
  disableStats: true,
});

window.Echo = echo;

const app = createApp(App);

app.config.globalProperties.$axios = axios;

app.use(router);
app.mount('#app');
