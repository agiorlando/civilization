import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';

const app = createApp(App);

// Optionally, if you want to type the global property, you could extend ComponentCustomProperties in a separate declaration file.
// For now, it's enough to assign axios directly:
app.config.globalProperties.$axios = axios;

app.use(router);
app.mount('#app');
