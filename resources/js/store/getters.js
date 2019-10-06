const getters = {
  errors: state => state.app.errors,
  setUpdateMessage: state => state.app.message.update,
  sidebar: state => state.app.sidebar,
  loggedIn: state => !!state.auth.token,
  roles: state => state.auth.roles,
  permissions: state => state.auth.permissions,
  user: state => state.auth.user,
  lang: state => state.lang.lang,
  addRouters: state => state.permission.addRouters,
  routers: state => state.permission.routers,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
};

export default getters;
