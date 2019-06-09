// import variables from '@/styles/element-variables.scss'
import defaultSettings from '@/settings';

const { showSettings, tagsView, fixedHeader, sidebarLogo, redirect } = defaultSettings;

const state = {
  // theme: variables.theme,
  showSettings: showSettings,
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  redirect: redirect,
};

const getters = {

};

const mutations = {
  CHANGE_SETTING: (state, { key, value }) => {
    if (state.hasOwnProperty(key)) {
      state[key] = value;
    }
  },
};

const actions = {
  changeSetting({ commit }, data) {
    commit('CHANGE_SETTING', data);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

