import {
  LOGIN,
  USER_INFO,
  SET_ROLES,
  SET_PERMISSIONS,
  SET_USER,
  FED_LOGOUT,
  SET_TOKEN,
  LOGOUT,
} from '../muation-types';

import { login, userInfo, logout } from '@/api/v1/auth';

import { getToken, removeToken, setToken } from '@/utils/auth';
import { resetRouter } from '@/router';

const DEFAULT_EXPIRE = 1;
const REMEMBER_ME_EXPIRE = 30;

const state = {
  token: getToken() || null,
  roles: [],
  permissions: [],
  userInfo: {},
};

const mutations = {
  [SET_ROLES](state, roles) {
    state.roles = roles;
  },
  [SET_PERMISSIONS](state, permissions) {
    state.permissions = permissions;
  },
  [SET_USER](state, user) {
    state.userInfo = user;
  },
  [SET_TOKEN](state, token) {
    state.token = token;
  },
};

const actions = {
  [LOGIN]({ commit }, payload) {
    return new Promise((resolve, reject) => {
      login(payload)
        .then(res => {
          const token = Object.freeze(res.data.data.access_token);
          setToken(token, payload.remember_me ? REMEMBER_ME_EXPIRE : DEFAULT_EXPIRE);
          commit(SET_TOKEN, token);
          resolve(res);
        })
        .catch(err => {
          reject(err);
        });
    });
  },
  [USER_INFO]({ commit, state }) {
    return new Promise((resolve, reject) => {
      userInfo(state.token)
        .then(res => {
          const { data } = Object.freeze(res.data);
          if (!data) {
            reject('Verification failed, please Login again.');
          }
          const { roles, permissions } = data;
          // roles must be a non-empty array
          if (!roles || roles.length <= 0) {
            reject('getInfo: roles must be a non-null array!');
          }
          commit(SET_ROLES, roles);
          commit(SET_PERMISSIONS, permissions);
          commit(SET_USER, data);
          resolve(data);
        })
        .catch(err => {
          reject(err);
        });
    });
  },
  [FED_LOGOUT]({ commit }) {
    return new Promise(resolve => {
      commit(SET_TOKEN, '');
      commit(SET_ROLES, []);
      removeToken();
      resolve();
    });
  },
  [LOGOUT]({ commit }) {
    return new Promise((resolve, reject) => {
      logout()
        .then(() => {
          removeToken();
          commit(SET_TOKEN, '');
          commit(SET_ROLES, []);
          resetRouter();
          resolve();
        })
        .catch(err => {
          reject(err);
        });
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
