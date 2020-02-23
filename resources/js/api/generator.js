import request from '@/utils/request';
import Resource from './resource';

export default class GeneratorResource extends Resource {
  constructor() {
    super('generators');
  }

  /**
   * check exist model
   * @param name
   * @returns {AxiosPromise}
   * @author tanmnt
   */
  checkModel(name) {
    return request({
      url: '/generators/check-model',
      method: 'get',
      params: { name },
    });
  }

  /**
   * check exists column
   * @param table
   * @param column
   * @returns {AxiosPromise}
   * @author tanmnt
   */
  checkColumn(table, column) {
    return request({
      url: '/generators/check-column',
      method: 'get',
      params: { table, column },
    });
  }

  /**
   * get model
   * @param model
   * @returns {AxiosPromise}
   * @author tanmnt
   */
  getModels(model) {
    return request({
      url: '/generators/get-models',
      method: 'get',
      params: { model },
    });
  }

  /**
   * get columns
   * @param table
   * @returns {AxiosPromise}
   * @author tanmnt
   */
  getColumns(table) {
    return request({
      url: '/generators/get-columns',
      method: 'get',
      params: { table },
    });
  }

  /**
   * generate relationship
   * @param relationship
   * @returns {AxiosPromise}
   * @author tanmnt
   */
  generateRelationship(relationship) {
    return request({
      url: '/generators/relationship',
      method: 'post',
      data: relationship,
    });
  }

  /**
   * generate diagram
   * @param model
   * @returns {AxiosPromise}
   * @author tanmnt
   */
  generateDiagram(model) {
    return request({
      url: '/generators/diagram',
      method: 'get',
      params: { model },
    });
  }
}
