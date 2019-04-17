import {
    SET_ERRORS,
    CLEAR_ERRORS,
    SET_TABLE_PAGE,
    SET_TABLE_LIMIT,
    UPDATE_MESSAGE
} from './muation-types'
import Vue from 'vue';
import Vuex from "vuex";

const state = () => ({
    errors: {},
    table: {
        page: 1,
        limit: 25
    },
    message: {
        update: false
    }
});

const getters = {
    errors(state, getters) {
        return state.errors;
    },
    pagePagination(state) {
        return state.table.page;
    },
    limitPagination(state) {
        return state.table.limit;
    },
    setUpdateMessage(state) {
        return state.message.update;
    }
};

const mutations = {
    [SET_ERRORS] (state, errors) {
        state.errors = errors;
    },
    [SET_TABLE_PAGE] (state, page) {
        state.table.page = page;
    },
    [SET_TABLE_LIMIT] (state, limit) {
        state.table.limit = limit;
    },
    [UPDATE_MESSAGE] (state, status) {
        state.message.update = status;
    }
};

const actions = {
    goBack({commit, dispatch, getters}) {
            window.history.go(-1);
    },
    [SET_ERRORS] ({commit, state}, errors) {
        commit(SET_ERRORS, errors);
    },
    [CLEAR_ERRORS] ({commit}) {
        commit(SET_ERRORS, {});
    },
    [SET_TABLE_PAGE] ({commit}, page) {
        commit(SET_TABLE_PAGE, page);
    },
    [SET_TABLE_LIMIT] ({commit}, page) {
        commit(SET_TABLE_LIMIT, page);
    },
    [UPDATE_MESSAGE] ({commit}, status) {
        commit(UPDATE_MESSAGE, status);
    }
};

Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
    },
    state: state(),
    getters,
    mutations,
    actions
});

export default store;
