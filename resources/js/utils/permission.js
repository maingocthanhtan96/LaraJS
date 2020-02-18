import store from '@/store';

/**
 * @param {Array} value
 * @returns {Boolean}
 * @example see @/views/permission/Directive.vue
 */
export default function checkPermission(value) {
  if (value && value instanceof Array && value.length > 0) {
    const permissions = store.getters && store.getters.permissions;
    const roles = store.getters && store.getters.roles;
    const requiredPermissions = value;

    const hasRole = roles.includes(store.state.settings.superAdmin);
    if (hasRole) {
      return true;
    }

    return permissions.some(permission => {
      return requiredPermissions.includes(permission);
    });
  } else {
    console.error(
      `Need permissions! Like v-permission="['manage permission','edit article']"`
    );
    return false;
  }
}
