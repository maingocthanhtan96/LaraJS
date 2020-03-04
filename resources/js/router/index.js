import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import dashboard from './modules/dashborad';
import administrator from './modules/administrator';
// {{$IMPORT_ROUTE_NOT_DELETE_THIS_LINE$}}

/**
 * Note: sub-menu only appear when route children.length >= 1
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    permissions: ['view menu administrator', 'manage user'] Visible for these permissions only
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if set true, the page will no be cached(default is false)
    affix: true                  if set true, the tag will affix in the tags-view
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
    tagViews: true not show tag view
  }
 */

export const constantRouterMap = [
  dashboard,
  // {{$ROUTE_CONSTANT_NOT_DELETE_THIS_LINE$}},
  {
    path: '/login',
    name: 'login',
    hidden: true,
    component: () => import('@/views/auth/Login'),
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    hidden: true,
    component: () => import('@/views/auth/ForgotPassword'),
  },
  {
    path: '/reset-password/:token',
    name: 'reset-password-form',
    hidden: true,
    component: () => import('@/views/auth/ResetPasswordForm'),
  },
  { path: '/404', hidden: true, component: () => import('@/views/errors/404') },
  { path: '/401', hidden: true, component: () => import('@/views/errors/401') },
  { path: '/', redirect: '/login', hidden: true },
  {
    path: '/redirect',
    component: () => import('@/layout'),
    hidden: true,
    children: [
      {
        path: ':path*',
        component: () => import('@/views/redirect'),
      },
    ],
  },
];

export const asyncRouterMap = [
  // {{$ROUTE_ASYNC_NOT_DELETE_THIS_LINE$}},
  administrator,
  { path: '*', redirect: '/404', hidden: true },
];

const createRouter = () =>
  new VueRouter({
    linkActiveClass: 'active', // active class
    mode: 'history',
    base: '/be',
    routes: constantRouterMap,
    scrollBehavior: to => {
      if (to.hash) {
        return { selector: to.hash };
      } else {
        return { x: 0, y: 0 };
      }
    },
  });

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
