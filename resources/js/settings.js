module.exports = {
  title: 'Larajs',

  /**
   * @type {string} url pages
   * @description Redirect before login
   */
  redirect: '/dashboard',

  /**
   * @type {boolean} true | false
   * @description Show translate
   */
  showTrans: true,

  /**
   * @type {boolean} true | false
   * @description Whether show the settings right-panel
   */
  showSettings: false,

  /**
   * @type {boolean} true | false
   * @description Whether need tagsView
   */
  tagsView: true,

  /**
   * @type {boolean} true | false
   * @description Whether fix the header
   */
  fixedHeader: true,

  /**
   * @type {boolean} true | false
   * @description Whether show the logo in sidebar
   */
  sidebarLogo: true,

  /**
   * @type {string} fade / fade-transform / zoom-fade / slide-fade / zoom-out / fade-bottom / fade-bottom-2x / fade-top / fade-top-2x
   * @default fade-transform
   * @description transition change page
   */
  routerTransition: 'fade-transform',

  /**
   * @type {string | array} 'production' | ['production', 'development']
   * @description Need show err logs component.
   * The default is only used in the production env
   * If you want to also use it in dev, you can pass ['production', 'development']
   */
  errorLog: 'production'
};
