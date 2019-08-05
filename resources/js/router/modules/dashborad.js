const dashboard = {
  path: '/dashboard',
  name: 'dashboard',
  component: () => import('@/layout'),
  redirect: '/dashboard/index',
  meta: {
    title: 'dashboard',
    icon: 'dashboard',
  },
  children: [
    {
      path: 'index',
      name: 'dashboard2',
      component: () => import('@/pages/dashboards/index'),
      meta: {
        title: 'dashboard',
        icon: 'dashboard',
      },
    },
  ],
};

export default dashboard;
