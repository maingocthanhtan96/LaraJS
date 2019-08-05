import request from '@/utils/request';

export function removeFile(file) {
  return request({
    url: 'upload-file/remove',
    method: 'get',
    params: {
      file,
    },
  });
}
