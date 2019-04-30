const dashboard = {
	path: '/dashboard',
	name: 'dashboard',
	component: () => import('@/layouts/AppMain'),
	redirect: '/dashboard/index',
	meta: {
		title: 'dashboard',
		icon: 'mdi mdi-shield-check',
	},
	children: [
		{
			path: 'index',
			name: 'dashboard1',
			component: () => import('@/pages/dashboards/index'),
			meta: {
				title: 'dashboard1',
				icon: 'dashboard1'
			},
			// children: [
			// 	{
			// 		path: 'dashboard2',
			// 		name: 'dashboard2',
			// 		component: () => import('@/pages/users/index'),
			// 		meta: {
			// 			title: 'Users List 2',
			// 			icon: 'chart 2'
			// 		},
			// 	}
			// ]
		}
	]
};

export default dashboard;