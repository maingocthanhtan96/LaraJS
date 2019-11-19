const getters = {
  errors: state => state.app.errors,
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  token: state => state.user.token,
  roles: state => state.user.roles,
  permissions: state => state.user.permissions,
  user: state => state.user.userInfo,
  lang: state => state.lang.lang,
  addRouters: state => state.permission.addRouters,
  routers: state => state.permission.routers,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
};

export default getters;
