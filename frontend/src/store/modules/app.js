import { logging } from '@fe/api/v1/app';

const state = {
  errors: {},
};
const mutations = {
  setErrors(state, errors) {
    state.errors = errors;
  },
};
const actions = {
  setErrors({ commit, state }, errors) {
    commit('setErrors', errors);
  },
  clearErrors({ commit }) {
    commit('setErrors', {});
  },
  logging({ commit }, payload) {
    return new Promise((resolve, reject) => {
      logging(payload)
        .then(res => resolve(res))
        .catch(err => reject(err));
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
