import axios from 'axios';
import store from '@fe/store';
import router from '@fe/router';
import md5 from 'md5';
import { getAccessToken } from './auth';

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
      const methods = ['post', 'put', 'patch'];
      config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
      if (process.env.MIX_HASH_KEY && methods.includes(config.method)) {
        config.headers['Hash-Key'] = md5(JSON.stringify(config.data) + process.env.MIX_HASH_KEY);
      }
    }
    if (Object.keys(store.getters.errors).length) {
      store.dispatch('app/setErrors', {});
    }
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
    if (res.data.errors) {
      await store.dispatch('app/setErrors', res.data.errors);
    }
    switch (true) {
      case res.status === 404:
        await router.replace({ path: '/404' });
        break;
      case process.env.NODE_ENV === 'production' && res.status === 500:
        await router.replace({ path: '/500' });
        break;
      case res.status === 401 && originalRequest.url !== '/v1/refresh-token':
        try {
          const passport = await store.dispatch('auth/refreshToKen');
          originalRequest.headers['Authorization'] = 'Bearer ' + passport.refresh_token;
          return service(originalRequest);
        } catch (e) {
          await router.replace({ name: 'Login' });
        }
        break;
    }
    return Promise.reject(error);
  }
);

export default service;
