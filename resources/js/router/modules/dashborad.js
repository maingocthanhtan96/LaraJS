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
      name: 'dashboard_index',
      component: () => import('@/views/dashboards/index'),
      meta: {
        title: 'dashboard',
        icon: 'dashboard',
      },
    },
  ],
};

export default dashboard;
