import request from '@fe/utils/request';

export function login(form) {
  return request({
    url: '/fe-login',
    method: 'post',
    data: form,
  });
}

export function refreshToKen(token) {
  return request({
    url: '/refresh-token',
    method: 'post',
    data: {
      refresh_token: token,
    },
  });
}

export function logout() {
  return request({
    url: '/fe-logout',
    method: 'get',
  });
}

export function memberInfo() {
  return request({
    url: `/members/info`,
    method: 'get',
  });
}
