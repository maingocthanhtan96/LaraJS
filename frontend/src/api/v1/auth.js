import request from '@fe/utils/request';

export function login(form) {
  return request({
    url: '/v1/fe-login',
    method: 'post',
    data: form,
  });
}

export function refreshToKen(token) {
  return request({
    url: '/v1/refresh-token',
    method: 'post',
    data: {
      refresh_token: token,
    },
  });
}

export function logout() {
  return request({
    url: '/v1/fe-logout',
    method: 'get',
  });
}

export function memberInfo() {
  return request({
    url: `/v1/members/info`,
    method: 'get',
  });
}
