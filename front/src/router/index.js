import { createRouter, createWebHistory } from 'vue-router';
import PollCreateView from '@/views/PollCreateView.vue';
import PollView from '@/views/PollView.vue';
import IndexView from '@/views/IndexView.vue';

const routes = [
  {path: '/', component: IndexView},
  { path: '/create', component: PollCreateView },
  { path: '/poll/:code', component: PollView },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
