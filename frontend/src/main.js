import Vue from 'vue';
import App from '@fe/views/App';
import router from '@fe/router';
import store from '@fe/store';
import '@/icons';

import '@fe/styles/bootstrap.scss';

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
