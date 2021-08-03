import request from '@/utils/request';

export function logging(errors) {
  return request({
    url: '/logging',
    method: 'post',
    data: errors,
  });
}
