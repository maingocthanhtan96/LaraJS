export default {
  inserted(el, binding, vnode) {
    if (binding.modifiers.color) {
      el.style.color =
        '#' +
        Math.random()
          .toString()
          .slice(2, 8);
    } else if (binding.modifiers.bg) {
      el.style.background =
        '#' +
        Math.random()
          .toString()
          .slice(2, 8);
    } else {
      console.error('Like v-random-color or v-random-color-bg');
    }
  }
};
