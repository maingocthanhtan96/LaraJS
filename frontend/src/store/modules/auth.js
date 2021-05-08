import { login, logout, refreshToKen, memberInfo } from '@fe/api/v1/auth';
import {
  getRefreshToken,
  removeAccessToken,
  removeRefreshToken,
  setAccessToken,
  setRefreshToken,
} from '@fe/utils/auth';
import { secondToDay } from '@fe/utils/date';

const state = {
  access_token: '',
  refresh_token: '',
  memberInfo: {},
  isLoggedIn: false,
};
const mutations = {
  SET_ACCESS_TOKEN(state, token) {
    state.access_token = token;
  },
  SET_REFRESH_TOKEN(state, token) {
    state.refresh_token = token;
  },
  SET_MEMBER_INFO(state, data) {
    state.memberInfo = data;
    state.isLoggedIn = !!Object.keys(data).length;
  },
};
const actions = {
  login({ commit }, payload) {
    return new Promise((resolve, reject) => {
      login(payload)
        .then(res => {
          const {
            data: { data: passport },
          } = res;
          const { access_token, expires_in, refresh_token } = passport;
          commit('SET_ACCESS_TOKEN', access_token);
          commit('SET_REFRESH_TOKEN', refresh_token);
          setAccessToken(access_token, secondToDay(expires_in));
          setRefreshToken(refresh_token);
          resolve(res);
        })
        .catch(err => {
          reject(err);
        });
    });
  },
  memberInfo({ commit }) {
    return new Promise((resolve, reject) => {
      memberInfo()
        .then(res => {
          const { data } = res;
          commit('SET_MEMBER_INFO', data.data);
          resolve(res);
        })
        .catch(err => {
          commit('SET_MEMBER_INFO', {});
          reject(err);
        });
    });
  },
  refreshToKen({ commit }) {
    return new Promise((resolve, reject) => {
      refreshToKen(getRefreshToken())
        .then(res => {
          const {
            data: { data: passport },
          } = res;
          const { access_token, expires_in, refresh_token } = passport;
          commit('SET_ACCESS_TOKEN', access_token);
          commit('SET_REFRESH_TOKEN', refresh_token);
          setAccessToken(access_token, secondToDay(expires_in));
          setRefreshToken(refresh_token);
          resolve(passport);
        })
        .catch(err => {
          commit('SET_ACCESS_TOKEN', '');
          commit('SET_REFRESH_TOKEN', '');
          removeAccessToken();
          removeRefreshToken();
          reject(err);
        });
    });
  },
  logout({ commit }) {
    return new Promise((resolve, reject) => {
      logout()
        .then(res => {
          commit('SET_ACCESS_TOKEN', '');
          commit('SET_REFRESH_TOKEN', '');
          commit('SET_MEMBER_INFO', {});
          removeAccessToken();
          removeRefreshToken();
          resolve(res);
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
