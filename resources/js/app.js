import App from './layouts/App';
import Vue from 'vue';
import router from './router';
import store from './store/index';
import i18n from './lang';
import pluginMixin from './plugins/mixins/index';
import ElementUI from 'element-ui';
import Vuetify from 'vuetify';
import '../stylus/main.styl';
import 'element-ui/lib/theme-chalk/index.css';

import './permission';

import * as filters from './filters';

// start third party
import axios from 'axios';
import moment from 'moment';
import lodash from 'lodash';
import {ServerTable} from 'vue-tables-2';

Vue.use(ServerTable, {
    perPage: 25, // page limit
    sortIcon: { base:'mdi', up:'mdi-sort-ascending', down:'mdi-sort-descending', is:'mdi-sort' }, // change icon sortable
    debounce: 700, //debounce query search
    saveState: true, // save cache
    pagination: {
        edge: true // disabled button fist/last
    },
    texts:{
        count: i18n.t('table.texts.count'),
        first: i18n.t('table.texts.first'),
        last: i18n.t('table.texts.last'),
        filter: i18n.t('table.texts.filter'),
        filterPlaceholder: i18n.t('table.texts.filterPlaceholder'),
        limit: i18n.t('table.texts.limit'),
        page: i18n.t('table.texts.page'),
        noResults: i18n.t('table.texts.noResults'),
        filterBy: i18n.t('table.texts.filterBy'),
        loading: i18n.t('table.texts.loading'),
        defaultOption: i18n.t('table.texts.defaultOption'),
        columns:  i18n.t('table.texts.columns')
    },
    }, false, 'bootstrap4');

// end third party

window.axios = axios;

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

Vue.use(pluginMixin);
Vue.use(Vuetify);

Vue.use(ElementUI, {
    size: localStorage.getItem('size') || 'medium',
    i18n: (key, value) => i18n.t(key, value)
});

// register global utility filters.
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
});

// disable show warning async validator
const warn = console.warn;
console.warn = (...args) => {
    if(typeof args[0] === 'string' && args[0].startsWith('async-validator:')) return;

    warn(...args);
};

export const app = new Vue({
    el: '#app',
    i18n,
    router,
    store,
    render: h => h(App)
});
