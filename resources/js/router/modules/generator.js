const generator = {
  path: '/generator',
  name: 'generator',
  component: () => import('@/layout'),
  redirect: '/generator/index',
  meta: {
    title: 'generator',
    icon: 'skill',
    roles: ['admin'],
  },
  children: [
    {
      path: 'index',
      name: 'generator_index',
      component: () => import('@/pages/generator'),
      meta: {
        title: 'generator',
        icon: 'skill',
        activeMenu: '/generator/index',
        roles: ['admin'],
      },
    },
    {
      path: 'form',
      name: 'generator_create',
      component: () => import('@/pages/generator/form'),
      meta: {
        title: 'generator_create',
        icon: 'skill',
        activeMenu: '/generator/index',
        roles: ['admin'],
      },
      hidden: true,
    },
    {
      path: 'form/:id(\\d+)',
      name: 'generator_edit',
      hidden: true,
      component: () => import('@/pages/generator/form'),
      meta: {
        tagsView: true,
        activeMenu: '/generator/index',
        title: 'generator_edit',
        roles: ['admin'],
      },
      props: route => {
        return {
          ...route,
          props: true,
        };
      },
    },
    {
      path: 'relationship/:id(\\d+)',
      name: 'generator_relationship',
      hidden: true,
      component: () => import('@/pages/generator/relationship'),
      meta: {
        tagsView: true,
        activeMenu: '/generator/index',
        title: 'generator_relationship',
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

export default generator;
