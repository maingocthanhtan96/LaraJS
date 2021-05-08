export function guest({ store }) {
  if (store.getters.isLoggedIn) {
    return store.state.settings.redirect;
  }
}

export function auth({ to, store }) {
  if (!store.getters.isLoggedIn) {
    return `/login?redirect=${to.path}`;
  }
}
