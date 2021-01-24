import { superAdmin } from '@/settings';

const administrator = {
  path: '/administrators',
  name: 'administrators',
  redirect: '/administrators/roles',
  component: () => import(/* webpackChunkName: "group/layout" */ '@/layout'),
  meta: {
    title: 'administrators',
    icon: 'admin',
    permissions: ['view menu role_permission'],
  },
  children: [
    {
      path: 'roles',
      name: 'roles',
      component: () =>
        import(
          /* webpackChunkName: "administrator/role-permission/index" */ '@/views/role-permission'
        ),
      meta: {
        title: 'role_permission',
        icon: 'role',
        permissions: ['manage permission'],
      },
    },
    {
      path: 'generator',
      name: 'generator',
      component: () =>
        import(
          /* webpackChunkName: "administrator/generator/index" */ '@/views/generator'
        ),
      meta: {
        title: 'generator',
        icon: 'tree-table',
        roles: [superAdmin],
      },
    },
    {
      path: 'generator/create',
      name: 'generator-create',
      component: () =>
        import(
          /* webpackChunkName: "administrator/generator/Form" */ '@/views/generator/Form'
        ),
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
      name: 'generator-edit',
      hidden: true,
      component: () =>
        import(
          /* webpackChunkName: "administrator/generator/Form" */ '@/views/generator/Form'
        ),
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
      path: 'generator/relationship/:id(\\d+)',
      name: 'generator-relationship',
      hidden: true,
      component: () =>
        import(
          /* webpackChunkName: "administrator/generator/Relationship" */ '@/views/generator/Relationship'
        ),
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
      name: 'user',
      component: () =>
        import(
          /* webpackChunkName: "administrator/user/index" */ '@/views/user'
        ),
      meta: {
        title: 'user',
        icon: 'user',
        roles: [superAdmin],
      },
    },
    {
      path: 'user/create',
      name: 'user-create',
      hidden: true,
      component: () =>
        import(
          /* webpackChunkName: "administrator/user/Form" */ '@/views/user/Form'
        ),
      meta: {
        title: 'user_create',
        activeMenu: '/administrators/user',
        roles: [superAdmin],
      },
    },
    {
      path: 'user/edit/:id(\\d+)',
      name: 'user-edit',
      hidden: true,
      component: () =>
        import(
          /* webpackChunkName: "administrator/user/Form" */ '@/views/user/Form'
        ),
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
