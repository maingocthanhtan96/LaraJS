import Resource from '@/api/resource';
import request from '@/utils/request';

export default class UserResource extends Resource {
  constructor() {
    super('/users');
  }

  update(id, resource) {
    return request({
      url: this.uri + '/' + id,
      method: 'post',
      data: resource,
    });
  }

  // {{$API_NOT_DELETE_THIS_LINE$}}
}
