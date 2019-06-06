import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import users from './modules/user';
import dashboard from './modules/dashborad';

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
  users,
  { path: '/login', name: 'login', hidden: true, component: () => import('@/pages/auth/login') },
  { path: '/404', hidden: true, component: () => import('@/pages/errors/404') },
  { path: '*', redirect: '/404', hidden: true },
  { path: '/', redirect: '/login', hidden: true },
  {
    path: '/redirect',
    component: () => import('@/layout'),
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/pages/redirect/index'),
      },
    ],
  },
];

export default new VueRouter({
  linkActiveClass: 'active',
  mode: 'history',
  routes: constantRouterMap,
  scrollBehavior: to => {
    if (to.hash) {
      return { selector: to.hash };
    } else {
      return { x: 0, y: 0 };
    }
  },
});

export const asyncRouterMap = [

];
