import {
    SET_ERRORS,
    CLEAR_ERRORS
} from '../store/muation-types';

import axios from 'axios';

import store from '../store/index';

// Create axios instance
const service = axios.create({
    baseURL: process.env.MIX_BASE_API,
    timeout: 10000 // Request timeout
});

// request
service.interceptors.request.use(
    config => {
        let token = localStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
        }
        return config;
    },
    error => {
        // Do something with request error
        console.log(error); // for debug
        return Promise.reject(error);
    }
);

// response pre-processing
service.interceptors.response.use(
    response => {
        if (response.headers.authorization) {
            localStorage.setItem('token', response.headers.authorization);
            response.data.token = response.headers.authorization
        }
        store.dispatch(CLEAR_ERRORS);
        return response.data;
    },
    error => {
        if(error.response) {
            store.dispatch(SET_ERRORS, error.response.data.errors);
            console.log('reponseErrors: ' + error); // for debug
            return Promise.reject(error);
        }
    }
);

export default service;
// export default function() {
//     function errorResponseHandler(error) {
//         // check for errorHandle config
//         if( error.config.hasOwnProperty('errorHandle') && error.config.errorHandle === false ) {
//             return Promise.reject(error);
//         }
//
//         // if has response show the error
//         if (error.response) {
//             store.dispatch(SET_ERRORS, error.response.data.errors);
//             return Promise.reject(error.response);
//         }
//     }
//
// // apply interceptor on response
//     axios.interceptors.response.use(
//         response => {
//             store.dispatch(CLEAR_ERRORS);
//             return response;
//         },
//         errorResponseHandler
//     );
// }
