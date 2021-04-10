import Resource from '@/api/resource';

export default class RoleResource extends Resource {
  constructor() {
    super('/v1/roles');
  }
}
