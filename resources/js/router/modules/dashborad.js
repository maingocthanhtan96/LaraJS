const dashboard = {
  path: '/dashboard',
  name: 'dashboard',
  component: () => import('@/layout'),
  redirect: '/dashboard/list',
  meta: {
    title: 'dashboard',
    icon: 'dashboard'
  },
  children: [
    {
      path: 'list',
      name: 'dashboard_list',
      component: () => import('@/views/dashboard/index'),
      meta: {
        title: 'dashboard',
        icon: 'dashboard'
      }
    }
  ]
};

export default dashboard;
