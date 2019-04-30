import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import users from './modules/user';
import dashboard from './modules/dashborad';

export const constantRouterMap = [
    users,
    dashboard,
    { path: '/login', name: 'login', hidden: true, component: () => import('@/pages/auth/login' )},
    { path: '/404', hidden: true,component: () => import('@/pages/errors/404') },
    { path: '*', redirect: '/404', hidden: true },
    { path: '/', redirect: '/login', hidden: true },
    {
        path: '/redirect',
        component: () => import('@/layouts/AppMain'),
        hidden: true,
        children: [
            {
                path: '/redirect/:path*',
                component: () => import('@/pages/redirect/index')
            }
        ]
    },
];

export default new VueRouter({
    linkActiveClass: 'active',
    mode: 'history',
    routes: constantRouterMap,
    scrollBehavior: to => {
        if(to.hash) {
            return {selector: to.hash}
        }else {
            return {x: 0, y: 0}
        }
    }
});

export const asyncRouterMap = [

];