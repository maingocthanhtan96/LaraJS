import Vue from 'vue';
import ElementUI from 'element-ui';
import '@/styles/element-variables.scss';
import App from '@/views/App.vue';
import router from '@/router';
import store from '@/store';
import i18n from '@/lang';
import '@/icons';
import '@/middleware';
import '@/utils/error-log';
import permission from '@/directive/permission';

// start third party
Vue.use(ElementUI, {
  size: localStorage.getItem('size') || 'medium',
  i18n: (key, value) => i18n.t(key, value),
});
// end third party

// register global utility filters.
import * as filters from './filters';
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

// register global directive
Vue.directive('permission', permission);

// register plugins
const mixinPlugins = require.context('@/plugins', true, /\.js$/);
mixinPlugins.keys().forEach(file => {
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

// Vue.config.performance = process.env.MIX_APP_ENV !== 'production';
Vue.config.productionTip = false;
new Vue({
  i18n,
  router,
  store,
  render: h => h(App),
}).$mount('#app');
