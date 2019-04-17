import {
    SET_ERRORS,
    CLEAR_ERRORS
} from '../store/muation-types';

import axios from 'axios';

import store from '../store/index';

export default function() {
    function errorResponseHandler(error) {
        // check for errorHandle config
        if( error.config.hasOwnProperty('errorHandle') && error.config.errorHandle === false ) {
            return Promise.reject(error);
        }

        // if has response show the error
        if (error.response) {
            store.dispatch(SET_ERRORS, error.response.data.errors);
            return Promise.reject(error.response);
        }
    }

// apply interceptor on response
    axios.interceptors.response.use(
        response => {
            store.dispatch(CLEAR_ERRORS);
            return response;
        },
        errorResponseHandler
    );
}
