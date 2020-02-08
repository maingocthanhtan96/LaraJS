const administrator = {
  path: '/administrator',
  name: 'administrator',
  redirect: '/administrator/roles',
  component: () => import('@/layout'),
  meta: {
    title: 'administrator',
    icon: 'admin',
    permissions: ['view menu role_permission']
  },
  children: [
    {
      path: 'roles',
      name: 'roles',
      component: () => import('@/views/rolePermission'),
      meta: {
        title: 'role_permission',
        icon: 'role',
        permissions: ['manage permission']
      }
    },
    {
      path: 'generator/list',
      name: 'generator_list',
      component: () => import('@/views/generator'),
      meta: {
        title: 'generator',
        icon: 'tree-table',
        roles: ['admin']
      }
    },
    {
      path: 'generator/form',
      name: 'generator_create',
      component: () => import('@/views/generator/form'),
      meta: {
        title: 'generator_create',
        icon: 'tree-table',
        activeMenu: '/administrator/generator/list',
        roles: ['admin']
      },
      hidden: true
    },
    {
      path: 'generator/form/:id(\\d+)',
      name: 'generator_edit',
      hidden: true,
      component: () => import('@/views/generator/form'),
      meta: {
        title: 'generator_edit',
        icon: 'tree-table',
        activeMenu: '/administrator/generator/list',
        roles: ['admin']
      },
      props: route => {
        return {
          ...route,
          props: true
        };
      }
    },
    {
      path: 'generator/relationship/:id(\\d+)',
      name: 'generator_relationship',
      hidden: true,
      component: () => import('@/views/generator/relationship'),
      meta: {
        title: 'generator_relationship',
        activeMenu: '/administrator/generator/list',
        roles: ['admin']
      },
      props: route => {
        return {
          ...route,
          props: true
        };
      }
    },
    {
      path: 'user/list',
      name: 'user_list',
      component: () => import('@/views/user/index'),
      meta: {
        title: 'user',
        icon: 'user',
        roles: ['admin']
      }
    },
    {
      path: 'user/form',
      name: 'user_create',
      hidden: true,
      component: () => import('@/views/user/form'),
      meta: {
        title: 'user_create',
        activeMenu: '/administrator/user/list',
        roles: ['admin']
      }
    },
    {
      path: 'user/form/:id(\\d+)',
      name: 'user_edit',
      hidden: true,
      component: () => import('@/views/user/form'),
      meta: {
        title: 'user_edit',
        activeMenu: '/administrator/user/list',
        roles: ['admin']
      },
      props: route => {
        return {
          ...route,
          props: true
        };
      }
    }
  ]
};

export default administrator;
