import { superAdmin } from '@/settings';

const administrator = {
  path: '/administrators',
  name: 'administrators',
  redirect: '/administrators/roles',
  component: () => import('@/layout'),
  meta: {
    title: 'administrators',
    icon: 'admin',
    permissions: ['view menu administrators'],
  },
  children: [
    {
      path: 'roles',
      name: 'Role',
      component: () => import('@/views/role-permission'),
      meta: {
        title: 'role_permission',
        icon: 'role',
        permissions: ['manage permission'],
      },
    },
    {
      path: 'generator',
      name: 'Generator',
      component: () => import('@/views/generator'),
      meta: {
        title: 'generator',
        icon: 'tree-table',
        roles: [superAdmin],
      },
    },
    {
      path: 'generator/create',
      name: 'GeneratorCreate',
      component: () => import('@/views/generator/Form'),
      meta: {
        title: 'generator_create',
        icon: 'tree-table',
        activeMenu: '/administrators/generator',
        roles: [superAdmin],
      },
      hidden: true,
    },
    {
      path: 'generator/edit/:id(\\d+)',
      name: 'GeneratorEdit',
      hidden: true,
      component: () => import('@/views/generator/Form'),
      meta: {
        title: 'generator_edit',
        icon: 'tree-table',
        activeMenu: '/administrators/generator',
        roles: [superAdmin],
      },
      props: route => {
        return {
          ...route,
          props: true,
        };
      },
    },
    {
      path: 'generator/relationship/:id(\\d+)?',
      name: 'GeneratorRelationship',
      hidden: true,
      component: () => import('@/views/generator/Relationship'),
      meta: {
        title: 'generator_relationship',
        activeMenu: '/administrators/generator',
        roles: [superAdmin],
      },
      props: route => {
        return {
          ...route,
          props: true,
        };
      },
    },
    {
      path: 'user',
      name: 'User',
      component: () => import('@/views/user'),
      meta: {
        title: 'user',
        icon: 'user',
        roles: [superAdmin],
      },
    },
    {
      path: 'user/create',
      name: 'UserCreate',
      hidden: true,
      component: () => import('@/views/user/Form'),
      meta: {
        title: 'user_create',
        activeMenu: '/administrators/user',
        roles: [superAdmin],
      },
    },
    {
      path: 'user/edit/:id(\\d+)',
      name: 'UserEdit',
      hidden: true,
      component: () => import('@/views/user/Form'),
      meta: {
        title: 'user_edit',
        activeMenu: '/administrators/user',
        roles: [superAdmin],
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
