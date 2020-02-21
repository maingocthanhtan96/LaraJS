// import variables from '@/styles/element-variables.scss'
import defaultSettings from '@/settings';

const {
  tagsView,
  fixedHeader,
  sidebarLogo,
  redirect,
  showTrans,
  routerTransitionTo,
  routerTransitionFrom,
  superAdmin
} = defaultSettings;

const state = {
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  redirect: redirect,
  showTrans: showTrans,
  routerTransitionTo: routerTransitionTo,
  routerTransitionFrom: routerTransitionFrom,
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
