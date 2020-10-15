import randomColor from '@/directive/randomColor/randomColor';

const install = function (Vue) {
  Vue.directive('randomColor', randomColor);
};

if (window.Vue) {
  window['randomColor'] = randomColor;
  Vue.use(install); // eslint-disable-line
}

randomColor.install = install;
export default randomColor;
