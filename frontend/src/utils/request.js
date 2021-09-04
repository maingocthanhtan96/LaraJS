import axios from 'axios';
import store from '@fe/store';
import router from '@fe/router';
import md5 from 'md5';

// eslint-disable-next-line no-unused-vars
import { getAccessToken } from './auth';

const methods = ['post', 'put', 'patch'];

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 60000, // Request timeout
});

// request
service.interceptors.request.use(
  config => {
    const token = getAccessToken() || false;
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
      if (process.env.MIX_HASH_KEY && methods.includes(config.method)) {
        config.headers['Hash-Key'] = md5(JSON.stringify(config.data) + process.env.MIX_HASH_KEY);
      }
    }
    store.dispatch('app/clearErrors');
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {
    store.dispatch('app/clearErrors');
    return response;
  },
  async error => {
    const res = error.response;
    const originalRequest = error.config;
    if (res) {
      if (res.status === 404) {
        await router.replace({ path: '/404' });
      }
      // if (res.status === 500) {
      //   router.replace({ path: '/500' });
      // }
      if (res.data.errors) {
        await store.dispatch(`app/setErrors`, res.data.errors);
      }
      if (res.status === 401 && originalRequest.url !== '/v1/refresh-token') {
        try {
          const passport = await store.dispatch('auth/refreshToKen');
          originalRequest.headers['Authorization'] = 'Bearer ' + passport.refresh_token;
          return service(originalRequest);
        } catch (e) {
          await router.replace({ name: 'Login' });
        }
      }
      return Promise.reject(error);
    }
  }
);

export default service;
