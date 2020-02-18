import store from '@/store';

export default {
  inserted(el, binding, vnode) {
    const { value } = binding;
    const permissions = store.getters && store.getters.permissions;
    const roles = store.getters && store.getters.roles;

    if (roles) {
      const hasRole = roles.includes(store.state.settings.superAdmin);
      if (hasRole) {
        return true;
      }
    }

    if (value && value instanceof Array && value.length > 0) {
      const requiredPermissions = value;
      const hasPermission = permissions.some(permission => {
        return requiredPermissions.includes(permission);
      });

      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el);
      }
    } else {
      throw new Error(
        `Permissions are required! Example: v-permission="['editor','manage permission']"`
      );
    }
  }
};
