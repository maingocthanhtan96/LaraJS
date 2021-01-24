const dashboard = {
  path: '/dashboard',
  component: () => import(/* webpackChunkName: "group/layout" */ '@/layout'),
  meta: {
    title: 'dashboard',
    icon: 'dashboard',
  },
  children: [
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: () =>
        import(/* webpackChunkName: "dashboard/index" */ '@/views/dashboard'),
      meta: {
        title: 'dashboard',
        icon: 'dashboard',
      },
    },
  ],
};

export default dashboard;
