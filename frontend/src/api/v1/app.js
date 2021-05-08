import request from '@fe/utils/request';

export function logging(errors) {
  return request({
    url: '/v1/logging',
    method: 'post',
    data: errors,
  });
}
