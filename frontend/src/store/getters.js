const getters = {
  errors: state => state.app.errors,
  isLoggedIn: state => state.auth.isLoggedIn,
};

export default getters;
