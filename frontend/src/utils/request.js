import axios from 'axios';

import store from '@fe/store';

import router from '@fe/router';

import { getToken, removeToken, setToken } from './auth';
import { matchInArray } from '@/utils/index';

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 60000, // Request timeout
});

// request
service.interceptors.request.use(
  config => {
    const token = getToken() || false;
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
    }
    store.dispatch('app/clearErrors');
    return config;
  },
  error => {
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
    store.dispatch('app/clearErrors');
    return response;
  },
  error => {
    const res = error.response;
    if (res) {
      const whiteList = store.state.settings.whiteList; // no redirect whitelist
      if (res.status === 404) {
        router.replace({ path: '/404' });
      }
      // if (res.status === 500) {
      //   router.replace({ path: '/500' });
      // }
      const currentUrl = router.history.current.path;
      if (!matchInArray(currentUrl, whiteList) && res.status === 401) {
        removeToken();
        router.replace({ path: '/login' });
      }
      if (res.data.errors) {
        store.dispatch(`app/setErrors`, res.data.errors);
      }
      console.log('Error response: ', res); // for debug

      return Promise.reject(error);
    }
  }
);

export default service;
