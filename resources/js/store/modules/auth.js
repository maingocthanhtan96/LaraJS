import {
    LOGIN,
    USER_INFO,
    SET_ROLES,
    SET_USER,
    FED_LOGOUT,
    SET_TOKEN,
    LOGOUT
} from '../muation-types';

import {
    login,
    userInfo,
    logout
} from '@/api/auth';

import {
    getToken, removeToken, setToken
} from "../../utils/auth";

const auth = {
    namespaced: true,
    state: {
        token: getToken() || null,
        roles: [],
        user: {}
    },
    getters: {
        loggedIn(state) {
            return !!state.token;
        },
        roles(state) {
            return state.roles;
        },
        user(state) {
            return state.user;
        }
    },
    mutations: {
        [LOGIN](state, token) {
            return state.token = token;
        },
        [SET_ROLES](state, roles) {
            return state.roles = roles;
        },
        [SET_USER](state, user) {
            return state.user = user;
        },
        [SET_TOKEN](state, token) {
            return state.token = token;
        }
    },
    actions: {
        [LOGIN]({commit}, payload) {
            return new Promise((resolve, reject) => {
                login(payload)
                    .then(res => {
                        const token = res.data.access_token;
                        setToken(token);
                        commit(SET_TOKEN, token);
                        resolve(res);
                    })
                    .catch(err => {
                        reject(err);
                    });
            })
        },
        [USER_INFO]({commit, state}) {
            return new Promise((resolve, reject) => {
                userInfo(state.token)
                    .then(res => {
                        let {data} = res.data;
                        if(data.role) {
                            commit(SET_ROLES, [data.role.name]);
                            commit(SET_USER, data);
                        }else {
                            reject('SET_ROLES error!');
                        }
                        resolve(res);
                    })
                    .catch(err => {
                        reject(err);
                    });
            })
        },
        [FED_LOGOUT]({commit}) {
            return new Promise(resolve => {
                removeToken();
                commit(SET_TOKEN, '');
                resolve();
            });
        },
        [LOGOUT]({commit}) {
            return new Promise((resolve, reject) => {
                logout()
                    .then(res => {
                        removeToken();
                        commit(SET_TOKEN, '');
                        commit(SET_ROLES, []);
                        resolve();
                    })
                    .catch(err => {
                        reject(err);
                    })
            });
        }
    }
};

export default auth
