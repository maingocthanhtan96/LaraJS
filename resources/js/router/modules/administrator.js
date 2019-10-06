const administrator = {
  path: '/administrator',
  name: 'administrator',
  redirect: '/administrator/roles',
  component: () => import('@/layout'),
  meta: {
    title: 'administrator',
    icon: 'admin',
    roles: ['admin'],
  },
  children: [
    {
      path: 'roles',
      name: 'roles',
      component: () => import('@/views/rolePermission'),
      meta: {
        title: 'role_permission',
        icon: 'skill',
      },
    },
    {
      path: 'generator/index',
      name: 'generator_index',
      component: () => import('@/views/generator'),
      meta: {
        title: 'generator',
        icon: 'skill',
      },
    },
    {
      path: 'generator/form',
      name: 'generator_create',
      component: () => import('@/views/generator/form'),
      meta: {
        title: 'generator_create',
        icon: 'skill',
        activeMenu: '/administrator/generator/index',
      },
      hidden: true,
    },
    {
      path: 'generator/form/:id(\\d+)',
      name: 'generator_edit',
      hidden: true,
      component: () => import('@/views/generator/form'),
      meta: {
        title: 'generator_edit',
        activeMenu: '/administrator/generator/index',
      },
      props: route => {
        return {
          ...route,
          props: true,
        };
      },
    },
    {
      path: 'generator/relationship/:id(\\d+)',
      name: 'generator_relationship',
      hidden: true,
      component: () => import('@/views/generator/relationship'),
      meta: {
        title: 'generator_relationship',
        activeMenu: '/administrator/generator/index',
      },
      props: route => {
        return {
          ...route,
          props: true,
        };
      },
    },
    {
      path: 'user/index',
      name: 'user_index',
      component: () => import('@/views/user/index'),
      meta: {
        title: 'user',
        icon: 'user',
      },
    },
    {
      path: 'user/form',
      name: 'user_create',
      hidden: true,
      component: () => import('@/views/user/form'),
      meta: {
        title: 'user_create',
        activeMenu: '/administrator/user/index',
      },
    },
    {
      path: 'user/form/:id(\\d+)',
      name: 'user_edit',
      hidden: true,
      component: () => import('@/views/user/form'),
      meta: {
        title: 'user_edit',
        activeMenu: '/administrator/user/index',
      },
      props: route => {
        return {
          ...route,
          props: true,
        };
      },
    },
  ],
};

export default administrator;
