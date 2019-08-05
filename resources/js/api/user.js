import request from '@/utils/request';
import Resource from '@/api/resource';

export default class UserResource extends Resource {
  constructor() {
    super('users');
  }

  roles() {
    return request({
      url: '/' + this.uri + '/roles/list',
      method: 'get',
    });
  }
}
