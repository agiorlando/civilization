import { createRouter, createWebHistory } from 'vue-router';

import Home from './pages/Home.vue';
import Civilizations from './pages/Civilizations.vue';
import Leaders from './pages/Leaders.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/civilizations',
    name: 'Civilizations',
    component: Civilizations,
  },
  {
    path: '/leaders',
    name: 'Leaders',
    component: Leaders,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
