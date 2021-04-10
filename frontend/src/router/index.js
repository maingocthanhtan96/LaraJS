import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

export const routes = [
  {
    path: '/',
    component: () => import('@fe/layout'),
    children: [
      {
        path: '/',
        name: 'Home',
        component: () => import('@fe/views/home'),
      },
    ],
  },
  {
    path: '/404',
    hidden: true,
    component: () => import('@fe/views/errors/404'),
  },
  {
    path: '/500',
    hidden: true,
    component: () => import('@fe/views/errors/500'),
  },
  { path: '*', redirect: '/404', hidden: true },
];

const router = new VueRouter({
  mode: 'history',
  linkActiveClass: 'active',
  base: process.env.APP_URL,
  routes,
});

export default router;
