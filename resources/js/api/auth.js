import request from '@/utils/request';

/**
 * login
 * @param form
 * @returns {AxiosPromise}
 * @author tanmnt
 */
export function login(form) {
  return request({
    url: '/login',
    method: 'post',
    data: form,
  });
}

/**
 * user info
 * @returns {AxiosPromise}
 * @author tanmnt
 */
export function userInfo() {
  return request({
    url: '/user-info',
    method: 'get',
  });
}

/**
 * logout
 * @returns {AxiosPromise}
 * @author tanmnt
 */
export function logout() {
  return request({
    url: '/logout',
    method: 'get',
  });
}

export function sendPasswordResetLink(form) {
  return request({
    url: '/forgot-password',
    method: 'post',
    data: form,
  });
}

export function callResetPassword(form) {
  return request({
    url: '/reset-password',
    method: 'post',
    data: form,
  });
}
