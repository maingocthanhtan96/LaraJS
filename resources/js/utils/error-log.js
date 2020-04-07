import Vue from 'vue';
import store from '@/store';
import { isString, isArray } from '@/utils/validate';
import settings from '@/settings';
import { ADD_ERROR_LOG, ADD_WARN_LOG } from '@/store/muation-types';

// you can set in settings.js
// errorLog:'production' | ['production', 'development']
const { errorLog: needErrorLog } = settings;

function checkNeed() {
  const env = process.env.MIX_APP_ENV;
  if (isString(needErrorLog)) {
    return env === needErrorLog;
  }
  if (isArray(needErrorLog)) {
    return needErrorLog.includes(env);
  }
  return false;
}
if (checkNeed()) {
  Vue.config.errorHandler = function(err, vm, info, a) {
    // Don't ask me why I use Vue.nextTick, it just a hack.
    // detail see https://forum.vuejs.org/t/dispatch-in-vue-config-errorhandler-has-some-problem/23500
    Vue.nextTick(() => {
      store.dispatch(`errorLog/${ADD_ERROR_LOG}`, {
        err,
        vm,
        info,
        url: window.location.href,
      });
      console.error(err, info);
    });
  };
  Vue.config.warnHandler = function(err, vm, trace, a) {
    // Don't ask me why I use Vue.nextTick, it just a hack.
    // detail see https://forum.vuejs.org/t/dispatch-in-vue-config-errorhandler-has-some-problem/23500
    Vue.nextTick(() => {
      store.dispatch(`errorLog/${ADD_WARN_LOG}`, {
        err,
        vm,
        trace,
        url: window.location.href,
      });
      console.warn(err, trace);
    });
  };
}
