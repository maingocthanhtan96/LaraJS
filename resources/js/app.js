
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue';
import router from './router';
import store from './store/index';
import VueI18n from 'vue-i18n';
import pluginMixin from './plugins/mixins/index';
import pluginAxios from './plugins/axios';
import enMessage from './lang/en.json';
import vnMessage from './lang/vn.json';
import config from './config.json';

// start third party
import axios from 'axios';
import moment from 'moment';
import lodash from 'lodash';
import {ServerTable} from 'vue-tables-2';

Vue.use(ServerTable, {perPage: 25,sortIcon: { base:'mdi', up:'mdi-sort-ascending', down:'mdi-sort-descending', is:'mdi-sort' }}, false, 'bootstrap4');

// end third party


Object.defineProperty(Vue.prototype, '$axios', {
    get() {
        return axios;
    }
});

Object.defineProperty(Vue.prototype, '$moment', {
   get() {
       return moment;
   }
});

Object.defineProperty(Vue.prototype, '$_', {
    get() {
        return lodash;
    }
});

Vue.use(VueI18n);
Vue.use(pluginMixin);
Vue.use(pluginAxios);

Vue.component('app-main', require('./layouts/AppMain').default);

const messages = {
    en: enMessage,
    vn: vnMessage
};

const i18n = new VueI18n({
    locale: 'en',
    messages,
    fallbackLocale: 'en'
});

axios.defaults.baseURL = config.dev.api;

Vue.config.productionTip = false;
const app = new Vue({
    el: '#app',
    i18n,
    router,
    store
});
