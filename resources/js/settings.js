module.exports = {
  title: 'LaraJS',

  /**
   * @type {string} url pages
   * @description Redirect before login
   */
  redirect: '/dashboard',

  /**
   * @type {number} offset time zone
   * @description Redirect before login
   */
  timeZone: process.env.MIX_TIME_ZONE || 'Asia/Tokyo', // Tokyo

  /**
   * @type {boolean} true | false
   * @description Show translate
   */
  showTrans: true,

  /**
   * @type {boolean} true | false
   * @description Show translate
   */
  showAPI: true,

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
   * @type {string}
   * @description Whether show the logo in sidebar
   */
  urlLogo: require('@/assets/images/logo/logo-tanmnt.png'),

  /**
   * @type {boolean} true | false
   * @description Multiple transition
   */
  moreTransition: true,

  /**
   * @type {string} fade / fade-transform-left / fade-transform-right / zoom-fade / slide-fade / zoom-out / fade-bottom / fade-bottom-2x / fade-top / fade-top-2x
   * @default fade-transform-left
   * @description transition change page
   */
  routerTransitionTo: 'fade-transform-right',
  routerTransitionFrom: 'fade-transform-left',

  /**
   * @type {string} name
   * @description role user
   */
  superAdmin: 'admin',

  /**
   * @type {string | array} 'production' | ['production', 'local']
   * @description Need show err logs component.
   * The default is only used in the production env
   * If you want to also use it in dev, you can pass ['production', 'development']
   */
  errorLog: 'production',
};
