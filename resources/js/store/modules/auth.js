import {
  LOGIN,
  USER_INFO,
  SET_ROLES,
  SET_USER,
  FED_LOGOUT,
  SET_TOKEN,
  LOGOUT,
} from '../muation-types';

import {
  login,
  userInfo,
  logout,
} from '@/api/auth';

import {
  getToken, removeToken, setToken,
} from '../../utils/auth';
import { resetRouter } from '@/router';

const auth = {
  namespaced: true,
  state: {
    token: getToken() || null,
    roles: [],
    user: {},
  },
  getters: {
    loggedIn(state) {
      return !!state.token;
    },
    roles(state) {
      return state.roles;
    },
    user(state) {
      return state.user;
    },
  },
  mutations: {
    [LOGIN](state, token) {
      state.token = token;
    },
    [SET_ROLES](state, roles) {
      state.roles = roles;
    },
    [SET_USER](state, user) {
      state.user = user;
    },
    [SET_TOKEN](state, token) {
      state.token = token;
    },
  },
  actions: {
    [LOGIN]({ commit }, payload) {
      return new Promise((resolve, reject) => {
        login(payload)
          .then(res => {
            const token = res.data.access_token;
            setToken(token);
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
            const { data } = res.data;
            if (!data) {
              reject('Verification failed, please Login again.');
            }
            const { roles } = data;
            // roles must be a non-empty array
            if (!roles || roles.length <= 0) {
              reject('getInfo: roles must be a non-null array!');
            }
            commit(SET_ROLES, roles);
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
        removeToken();
        commit(SET_TOKEN, '');
        commit(SET_ROLES, []);
        removeToken();
        resolve();
      });
    },
    [LOGOUT]({ commit }) {
      return new Promise((resolve, reject) => {
        logout()
          .then(res => {
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
  },
};

export default auth;
