const user = {
  path: '/users',
  name: 'user',
  redirect: '/users/index',
  component: () => import('@/layout'),
  meta: {
    title: 'user',
    icon: 'user',
    roles: ['admin'],
  },
  children: [
    {
      path: 'index',
      name: 'user_index',
      component: () => import('@/pages/users/index'),
      meta: {
        title: 'user',
        icon: 'user',
        roles: ['admin'],
        activeMenu: '/users',
      },
      hidden: true,
    },
    {
      path: 'form',
      name: 'user_create',
      hidden: true,
      component: () => import('@/pages/users/form'),
      meta: {
        activeMenu: '/users',
        title: 'user_create',
        roles: ['admin'],
      },
    },
    {
      path: 'form/:id(\\d+)',
      name: 'user_edit',
      hidden: true,
      component: () => import('@/pages/users/form'),
      meta: {
        tagsView: true,
        activeMenu: '/users',
        title: 'user_edit',
        roles: ['admin'],
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

export default user;
