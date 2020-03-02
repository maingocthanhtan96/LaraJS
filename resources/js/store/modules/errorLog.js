import request from '@/utils/request';

const state = {
  logs: [],
};

const mutations = {
  ADD_ERROR_LOG: (state, log) => {
    state.logs.push(log);
  },
  CLEAR_ERROR_LOG: state => {
    state.logs.splice(0);
  },
};

const actions = {
  addErrorLog({ commit }, log) {
    return request({
      url: '/logs/error',
      method: 'post',
      data: {
        err: log.err.message,
        url: log.url,
        info: log.info,
      },
    });
  },
  addWarnLog({ commit }, log) {
    return request({
      url: '/logs/warn',
      method: 'post',
      data: {
        err: log.err,
        url: log.url,
        trace: log.trace,
      },
    });
  },
  clearErrorLog({ commit }) {
    commit('CLEAR_ERROR_LOG');
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
