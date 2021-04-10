import defaultSettings from '@fe/settings';

const { title, redirect, routerTransitionTo, routerTransitionFrom } = defaultSettings;

const state = {
  title,
  routerTransitionTo,
  routerTransitionFrom,
  redirect,
};
const mutations = {};
const actions = {};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
