const users = {
  path: '/users',
  name: 'users',
  redirect: '/users/index',
  component: () => import('@/layout'),
  meta: {
    title: 'users',
    icon: 'user',
    roles: ['admin'],
  },
  children: [
    {
      path: 'index',
      name: 'user_index',
      component: () => import('@/pages/users/index'),
      meta: {
        title: 'users',
        icon: 'user',
        roles: ['admin'],
        activeMenu: '/users',
      },
      hidden: true,
    },
    {
      path: 'form',
      name: 'user_form',
      hidden: true,
      component: () => import('@/pages/users/form'),
      meta: {
        activeMenu: '/users',
        title: 'User Create',
        roles: ['admin'],
      },
    },
    {
      path: 'form/:id(\\d+)',
      name: 'user_form_edit',
      hidden: true,
      component: () => import('@/pages/users/form'),
      meta: {
        tagsView: true,
        activeMenu: '/users',
        title: 'User Edit',
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

export default users;
