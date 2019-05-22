import {
    SET_ERRORS,
    CLEAR_ERRORS,
    UPDATE_MESSAGE
} from './muation-types'
import Vue from 'vue';
import Vuex from "vuex";

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
// https://webpack.js.org/guides/dependency-management/#requirecontext
const modulesFiles = require.context('./modules', true, /\.js$/);
// it will auto require all vuex module from modules file
const modules = modulesFiles.keys().reduce((modules, modulePath) => {
    const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1');
    const value = modulesFiles(modulePath);
    modules[moduleName] = value.default;
    return modules;
}, {});

const store = new Vuex.Store({
    modules,
    state: state(),
    getters,
    mutations,
    actions
});

export default store;
