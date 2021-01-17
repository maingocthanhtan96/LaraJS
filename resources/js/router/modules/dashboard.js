const dashboard = {
  path: '/dashboard',
  component: () => import(/* webpackChunkName: "group-dashboard" */ '@/layout'),
  meta: {
    title: 'dashboard',
    icon: 'dashboard',
  },
  children: [
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: () =>
        import(/* webpackChunkName: "group-dashboard" */ '@/views/dashboard'),
      meta: {
        title: 'dashboard',
        icon: 'dashboard',
      },
    },
  ],
};

export default dashboard;
