import Vue from 'vue';
import ElementUI from 'element-ui';
import App from '@/views/App.vue';
import router from '@/router';
import store from '@/store';
import i18n from '@/lang';
import '@/icons';
import '@/permission';

// start third party
import axios from 'axios';
window.axios = axios;
import {ServerTable, Event} from 'vue-tables-2';
window.Event = Event;
Vue.use(ServerTable, {
  perPage: 25, // page limit
  sortIcon: {base: 'mdi', up: 'mdi-sort-ascending', down: 'mdi-sort-descending', is: 'mdi-sort'}, // change icon sortable
  debounce: 700, // debounce query search
  saveState: true, // save cache
  pagination: {
    edge: true, // disabled button fist/last
  },
  texts: {
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
    columns: i18n.t('table.texts.columns'),
  },
}, false, 'bulma', require('./template/datables'));
// end third party

Vue.use(ElementUI, {
  size: localStorage.getItem('size') || 'medium',
  i18n: (key, value) => i18n.t(key, value),
});

// register global utility filters.
import * as filters from './filters';
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

// register plugins
const mixinPlugins = require.context('@/plugins', true, /\.js$/);
mixinPlugins.keys().forEach((file) => {
  const mixin = file.replace(/^\.\/(.*)\w*$/, '$1');
  Vue.use(require(`./plugins/${mixin}`).default);
});

// disable show warning async validator
const warn = console.warn;
console.warn = (...args) => {
  if (typeof args[0] === 'string' && args[0].startsWith('async-validator:')) {
    return;
  }
  warn(...args);
};

Vue.config.productionTip = false;
new Vue({
  el: '#app',
  i18n,
  router,
  store,
  render: h => h(App),
});
