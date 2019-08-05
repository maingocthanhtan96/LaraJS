import {
  SET_ERRORS,
  CLEAR_ERRORS,
} from '../store/muation-types';

import { Message } from 'element-ui';

import axios from 'axios';

import store from '../store/index';

import router from '@/router/index';

import {
  getToken, setToken,
} from './auth';

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 10000, // Request timeout
});

// request
service.interceptors.request.use(
  config => {
    const token = getToken() || false;
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
    }
    store.dispatch(CLEAR_ERRORS);
    return config;
  },
  error => {
    // Do something with request error
    console.log('Error request: ', error); // for debug
    return Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    if (response.headers.authorization) {
      setToken(response.headers.authorization);
      response.data.token = response.headers.authorization;
    }
    store.dispatch(CLEAR_ERRORS);
    return response;
  },
  error => {
    const res = error.response;
    if (res) {
      if (res.status === 404) {
        router.replace({ path: '/404' });
      }
      if (res.data.errors) {
        store.dispatch(SET_ERRORS, res.data.errors);
      } else {
        Message({
          message: res.data.message || 'Error',
          type: 'error',
          duration: 5 * 1000,
        });
      }
      console.log('Error response: ' + res); // for debug

      return Promise.reject(error);
    }
  }
);

export default service;
