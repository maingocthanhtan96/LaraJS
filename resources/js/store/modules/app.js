import { CLEAR_ERRORS, SET_ERRORS } from '../muation-types';
import Cookies from 'js-cookie';

const state = {
  errors: {},
  table: {
    page: 1,
    limit: 25,
  },
  isCollapse: false,
  collapse: '199px',
  sidebar: {
    opened: Cookies.get('sidebarStatus') ? !!+Cookies.get('sidebarStatus') : true,
    withoutAnimation: false,
  },
  device: 'desktop',
  size: Cookies.get('size') || 'medium',
};

const mutations = {
  [SET_ERRORS](state, errors) {
    state.errors = errors;
  },
  TOGGLE_SIDEBAR: state => {
    state.sidebar.opened = !state.sidebar.opened;
    state.sidebar.withoutAnimation = false;
    if (state.sidebar.opened) {
      Cookies.set('sidebarStatus', 1);
    } else {
      Cookies.set('sidebarStatus', 0);
    }
  },
  CLOSE_SIDEBAR: (state, withoutAnimation) => {
    Cookies.set('sidebarStatus', 0);
    state.sidebar.opened = false;
    state.sidebar.withoutAnimation = withoutAnimation;
  },
  TOGGLE_DEVICE: (state, device) => {
    state.device = device;
  },
  SET_SIZE: (state, size) => {
    state.size = size;
    Cookies.set('size', size);
  },
};

const actions = {
  [SET_ERRORS]({ commit, state }, errors) {
    commit(SET_ERRORS, errors);
  },
  [CLEAR_ERRORS]({ commit }) {
    commit(SET_ERRORS, {});
  },
  toggleSideBar({ commit }) {
    commit('TOGGLE_SIDEBAR');
  },
  closeSideBar({ commit }, { withoutAnimation }) {
    commit('CLOSE_SIDEBAR', withoutAnimation);
  },
  toggleDevice({ commit }, device) {
    commit('TOGGLE_DEVICE', device);
  },
  setSize({ commit }, size) {
    commit('SET_SIZE', size);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
