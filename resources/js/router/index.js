import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

export default new VueRouter({
    linkActiveClass: 'active',
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'login',
            components: {
                login: () => import('@/pages/auth/login')
            }
        },
        { path: '/404',components: {page404: () => import('@/pages/errors/404')} },
        { path: '*', redirect: '/404' }
    ],

    scrollBehavior: to => {
        if(to.hash) {
            return {selector: to.hash}
        }else {
            return {x: 0, y: 0}
        }
    }
});
