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
    url: '/user',
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
