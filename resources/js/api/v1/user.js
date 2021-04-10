import Resource from '@/api/resource';

export default class UserResource extends Resource {
  constructor() {
    super('/v1/users');
  }

  // {{$API_NOT_DELETE_THIS_LINE$}}
}
