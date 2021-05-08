import NProgress from 'nprogress'; // progress bar
import router from '@fe/router';
import store from '@fe/store';
import { getAccessToken } from '@fe/utils/auth';
import * as guards from '@fe/router/middleware/guards';

NProgress.configure({ showSpinner: true }); // NProgress Configuration

router.beforeEach(async (to, from, next) => {
  try {
    // start progress bar
    NProgress.start();
    document.title = to.meta.title || store.state.settings.title;
    if (!!getAccessToken() && !store.getters.isLoggedIn) {
      await store.dispatch('auth/memberInfo');
    }
    // determine whether the user has logged in
    if (!to.meta.middleware) {
      return next();
    }

    const middleware = to.meta.middleware;

    const context = {
      to,
      from,
      next,
      store,
    };
    for (const guard of middleware) {
      const funcNext = guards[guard](context);
      if (funcNext) {
        return next(funcNext);
      }
    }
    return next();
  } catch (e) {
    return NProgress.done();
  }
});

router.afterEach(() => {
  return NProgress.done();
});
