import { asyncRouterMap, constantRouterMap } from '@/router';
import { GENERATE_ROUTES, SET_ROUTERS } from '../muation-types';

/**
 * Check if it matches the current user right by meta.role
 * @param {String[]} roles
 * @param {String[]} permissions
 * @param route
 */
function canAccess(roles, permissions, route) {
  if (route.meta) {
    let hasRole = true;
    let hasPermission = true;
    if (route.meta.roles || route.meta.permissions) {
      // If it has meta.roles or meta.permissions, accessible = hasRole || permission
      hasRole = false;
      hasPermission = false;
      if (route.meta.roles) {
        hasRole = roles.some(role => route.meta.roles.includes(role));
      }

      if (route.meta.permissions) {
        hasPermission = permissions.some(permission =>
          route.meta.permissions.includes(permission)
        );
      }
    }

    return hasRole || hasPermission;
  }

  // If no meta.roles/meta.permissions inputted - the route should be accessible
  return true;
}

/**
 * Find all routes of this role
 * @param routes asyncRoutes
 * @param roles
 */
function filterAsyncRoutes(routes, roles, permissions) {
  const res = [];

  routes.forEach(route => {
    const tmp = { ...route };
    if (canAccess(roles, permissions, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(tmp.children, roles, permissions);
      }
      res.push(tmp);
    }
  });

  return res;
}

const state = {
  routers: [],
  addRouters: []
};

const mutations = {
  [SET_ROUTERS]: (state, routers) => {
    state.addRouters = routers;
    state.routers = constantRouterMap.concat(routers);
  }
};

const actions = {
  [GENERATE_ROUTES]({ commit }, { roles, permissions }) {
    return new Promise(resolve => {
      let accessedRouters;
      if (roles.includes('admin')) {
        accessedRouters = asyncRouterMap;
      } else {
        accessedRouters = filterAsyncRoutes(asyncRouterMap, roles, permissions);
      }
      commit(SET_ROUTERS, accessedRouters);
      resolve(accessedRouters);
    });
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
