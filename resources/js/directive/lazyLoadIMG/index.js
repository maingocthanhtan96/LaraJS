import lazyLoad from './lazyLoad';

const install = function(Vue) {
  Vue.directive('lazy-load-img', lazyLoad);
};

if (window.Vue) {
  window['lazy-load-img'] = lazyLoad;
  Vue.use(install); // eslint-disable-line
}

lazyLoad.install = install;
export default lazyLoad;
