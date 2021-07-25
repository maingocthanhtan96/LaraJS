module.exports = {
  title: 'LaraJS',

  /**
   * @type {string} url pages
   * @description Redirect before login
   */
  redirect: '/',

  /**
   * @type {string} fade / fade-transform-left / fade-transform-right / zoom-fade / slide-fade / zoom-out / fade-bottom / fade-bottom-2x / fade-top / fade-top-2x
   * @default fade-transform-left
   * @description transition change page
   */
  routerTransitionTo: 'fade-transform-right',
  routerTransitionFrom: 'fade-transform-left',

  /**
   * @type {array}
   * @description No redirect whitelist
   */
  whiteList: [],

  /**
   * @type {string | array} 'production' | ['production', 'local']
   * @description Need show err logs component.
   * The default is only used in the production env
   * If you want to also use it in dev, you can pass ['production', 'development']
   */
  errorLog: 'production',
};
