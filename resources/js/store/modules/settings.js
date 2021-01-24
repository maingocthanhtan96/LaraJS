// import variables from '@/styles/element-variables.scss'
import defaultSettings from '@/settings';

const {
  tagsView,
  fixedHeader,
  sidebarLogo,
  urlLogo,
  whiteList,
  redirect,
  timeZone,
  showTrans,
  showAPI,
  moreTransition,
  routerTransitionTo,
  routerTransitionFrom,
  superAdmin,
  title,
} = defaultSettings;

const state = {
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  sidebarLogo: sidebarLogo,
  urlLogo: urlLogo,
  whiteList: whiteList,
  redirect: redirect,
  timeZone: timeZone,
  showTrans: showTrans,
  showAPI: showAPI,
  moreTransition,
  routerTransitionTo: routerTransitionTo,
  routerTransitionFrom: routerTransitionFrom,
  superAdmin: superAdmin,
  title: title,
};

const mutations = {
  CHANGE_SETTING: (state, { key, value }) => {
    if (Object.prototype.hasOwnProperty.call(state, key)) {
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
