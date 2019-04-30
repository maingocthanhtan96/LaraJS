import {
    SET_ERRORS,
    CLEAR_ERRORS,
    UPDATE_MESSAGE
} from './muation-types'
import Vue from 'vue';
import Vuex from "vuex";

import {
    lang,
    auth,
    permission,
    tagsView
} from './modules';

// import lang from './modules/lang'

const state = () => ({
    errors: {},
    table: {
        page: 1,
        limit: 25
    },
    message: {
        update: false
    },
    isCollapse: false,
    collapse: '199px'
});

const getters = {
    errors(state, getters) {
        return state.errors;
    },
    setUpdateMessage(state) {
        return state.message.update;
    }
};

const mutations = {
    [SET_ERRORS] (state, errors) {
        state.errors = errors;
    },
    [UPDATE_MESSAGE] (state, status) {
        state.message.update = status;
    }
};

const actions = {
    [SET_ERRORS] ({commit, state}, errors) {
        commit(SET_ERRORS, errors);
    },
    [CLEAR_ERRORS] ({commit}) {
        commit(SET_ERRORS, {});
    },
    [UPDATE_MESSAGE] ({commit}, status) {
        commit(UPDATE_MESSAGE, status);
    }
};

Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        lang,
        auth,
        permission,
        tagsView
    },
    state: state(),
    getters,
    mutations,
    actions
});

export default store;
