const dashboard = {
  path: '/dashboard',
  name: 'dashboard',
  component: () => import('@/layout'),
  redirect: '/dashboard/index',
  meta: {
    title: 'Permission',
    icon: 'lock',
  },
  children: [
    {
      path: 'index',
      name: 'dashboard2',
      component: () => import('@/pages/dashboards/index'),
      meta: {
        title: 'Dashboard',
        icon: 'lock',
      },
    },
  ],
};

export default dashboard;
