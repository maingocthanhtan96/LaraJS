import router from './router'
import store from './store'
import { Message } from 'element-ui'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

import {FED_LOGOUT, GENERATE_ROUTES, USER_INFO} from "./store/muation-types";
import getPageTitle from "./utils/get-page-title"

NProgress.configure({ showSpinner: true });// NProgress Configuration

// permission judge function
function hasPermission(roles, permissionRoles) {
	if (roles.indexOf('Admin') >= 0) {
		// Admin should have all permissions
		return true;
	}
	if (!permissionRoles) {
		return true;
	}
	return roles.some(role => permissionRoles.indexOf(role) >= 0);
}

const whiteList = ['/login']; // no redirect whitelist

router.beforeEach((to, from, next) => {
	NProgress.start(); // Start the progress bar
	document.title = getPageTitle(to.meta.title)
	if (store.getters['auth/loggedIn']) {
		if (to.path === '/login') {
			// Skip login page for logged users
			next({ name: 'users' }); // change path default when exist token
			NProgress.done();
		} else {
			if (store.getters['auth/roles'].length === 0) {
				store.dispatch(`auth/${USER_INFO}`)
					.then(res => { // Get user information
						let {data} = res.data;
						const roles = [data.role.name];
						// next()
						store.dispatch(`permission/${GENERATE_ROUTES}`, { roles })
							.then(() => { // Get all routers based on current role
								router.addRoutes(store.getters['permission/addRouters']);
								next({ ...to, replace: true }); // Just to make sure addRoutes is done ,set the replace: true so the navigation will not leave a history record
							});
					})
					.catch((err) => {
						store.dispatch(`auth/${FED_LOGOUT}`).then(() => {
							Message({
								message: err.message || 'Verification failed, please login again',
								type: 'error',
								duration: 5 * 1000
							});
							next({ path: '/' });
					});
				});
			} else {
				// Double check permission role
				if (hasPermission(store.getters['permission/addRouters'], to.meta.roles)) {
					next();
				} else {
					next({ path: '/401', replace: true, query: { noGoBack: true } })
				}
			}
		}
	} else {
		if (whiteList.indexOf(to.path) !== -1) {
			next();
		} else {
			next(`/login?redirect=${to.path}`); // Redirect to login page if for unauthorized users
			NProgress.done()
		}
	}
});


// After router hooks are resolved, finish progress bar
router.afterEach(() => {
	NProgress.done();
});
