import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@fe/views/home';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.APP_URL,
  routes,
});

export default router;
