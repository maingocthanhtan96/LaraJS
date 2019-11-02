// import variables from '@/styles/element-variables.scss'
import defaultSettings from '@/settings';

const { showSettings, tagsView, fixedHeader, sidebarLogo, redirect, showTrans } = defaultSettings;

const state = {
  // theme: variables.theme,
  showSettings: showSettings,
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  redirect: redirect,
  showTrans: showTrans,
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
  mutations,
  actions,
};

