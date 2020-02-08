import request from '@/utils/request';

/**
 * remove file
 * @param file
 * @returns {AxiosPromise}
 * @author tanmnt
 */
export function removeFile(file) {
  return request({
    url: 'upload-file/remove',
    method: 'get',
    params: {
      file
    }
  });
}
