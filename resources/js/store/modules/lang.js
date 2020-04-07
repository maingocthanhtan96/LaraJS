import { SET_LANG } from '../muation-types';
import i18n from '@/lang';

const state = {
  lang: localStorage.getItem('lang') || 'ja',
};

const mutations = {
  [SET_LANG](state, lang) {
    i18n.locale = lang;
    state.lang = lang;
    // return state.lang = lang;
  },
};

const actions = {
  [SET_LANG]({ commit }, lang) {
    localStorage.setItem('lang', lang);
    commit(SET_LANG, lang);
    fetch(`/api/language/${lang}`);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
