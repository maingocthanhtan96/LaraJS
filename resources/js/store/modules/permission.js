import { asyncRouterMap, constantRouterMap } from '@/router';
import { GENERATE_ROUTES, SET_ROUTERS } from '../muation-types';

/**
 * Check if it matches the current user right by meta.roles
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
  if (route.meta && route.meta.roles) {
    return roles.some(role => route.meta.roles.includes(role));
  } else {
    return true;
  }
}

/**
 * Find all routers of this role
 * @param routes asyncRouterMap
 * @param roles
 */
function filterAsyncRouter(routes, roles) {
  const res = [];
  routes.forEach(route => {
    const tmp = { ...route };
    if (hasPermission(roles, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRouter(tmp.children, roles);
      }
      res.push(tmp);
    }
  });

  return res;
}

const permission = {
  namespaced: true,
  state: {
    routers: [],
    addRouters: [],
  },
  getters: {
    addRouters(state) {
      return state.addRouters;
    },
    routers(state) {
      return state.routers;
    },
  },
  mutations: {
    [SET_ROUTERS]: (state, routers) => {
      state.addRouters = routers;
      state.routers = constantRouterMap.concat(routers);
    },
  },
  actions: {
    [GENERATE_ROUTES]({ commit }, data) {
      return new Promise(resolve => {
        const { roles } = data;
        let accessedRouters;
        if (roles.includes('Admin')) {
          accessedRouters = asyncRouterMap;
        } else {
          accessedRouters = filterAsyncRouter(asyncRouterMap, roles);
        }
        commit(SET_ROUTERS, accessedRouters);
        resolve();
      });
    },
  },
};

export default permission;
