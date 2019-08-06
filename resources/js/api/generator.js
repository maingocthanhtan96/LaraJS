import request from '@/utils/request';
import Resource from './resource';

export default class GeneratorResource extends Resource {
  constructor() {
    super('generators');
  }

  checkModel(name) {
    return request({
      url: '/generators/check-model',
      method: 'get',
      params: { name },
    });
  }

  checkColumn(table, column) {
    return request({
      url: '/generators/check-column',
      method: 'get',
      params: { table, column },
    });
  }

  getModels(model) {
    return request({
      url: '/generators/get-models',
      method: 'get',
      params: { model },
    });
  }

  getColumns(table) {
    return request({
      url: '/generators/get-columns',
      method: 'get',
      params: { table },
    });
  }

  generateRelationship(relationship) {
    return request({
      url: '/generators/relationship',
      method: 'post',
      data: relationship,
    });
  }

  generateDiagram(model) {
    return request({
      url: '/generators/diagram',
      method: 'get',
      params: { model },
    });
  }
}
