import {
    SET_LANG
} from '../muation-types';
import i18n from '@/lang';

const lang = {
    namespaced: true,
    state: {
        lang: localStorage.getItem('lang') || 'en'
    },
    getters: {
        lang(state) {
            return state.lang;
        }
    },
    mutations: {
        [SET_LANG] (state, lang) {
            i18n.locale = lang;
            state.lang = lang;
            // return state.lang = lang;
        }
    },
    actions: {
        [SET_LANG] ({commit}, lang) {
            commit(SET_LANG, lang);
        }
    }
};

export {lang}
