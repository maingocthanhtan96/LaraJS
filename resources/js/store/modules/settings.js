// import variables from '@/styles/element-variables.scss'
import defaultSettings from '@/settings';

const {
  showSettings,
  tagsView,
  fixedHeader,
  sidebarLogo,
  redirect,
  showTrans,
  routerTransition,
  superAdmin
} = defaultSettings;

const state = {
  showSettings: showSettings,
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  redirect: redirect,
  showTrans: showTrans,
  routerTransition: routerTransition,
  superAdmin: superAdmin
};

const mutations = {
  CHANGE_SETTING: (state, { key, value }) => {
    if (state.hasOwnProperty(key)) {
      state[key] = value;
    }
  }
};

const actions = {
  changeSetting({ commit }, data) {
    commit('CHANGE_SETTING', data);
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions
};
