import defaultSettings from '@fe/settings';

const { title, routerTransitionTo, routerTransitionFrom } = defaultSettings;

const state = {
  title,
  routerTransitionTo,
  routerTransitionFrom,
};
const mutations = {};
const actions = {};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
