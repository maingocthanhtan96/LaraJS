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
   * @type {string} fade / fade-transform-left / fade-transform-left / zoom-fade / slide-fade / zoom-out / fade-bottom / fade-bottom-2x / fade-top / fade-top-2x
   * @default fade-transform
   * @description transition change page
   */
  routerTransitionTo: 'fade-transform-left',
  routerTransitionFrom: 'fade-transform-right',

  /**
   * @type {string} name
   * @description role user
   */
  superAdmin: 'admin'
};
