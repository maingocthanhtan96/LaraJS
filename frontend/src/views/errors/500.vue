<template>
  <div class="tw-h-screen">
      <div id="notfound">
        <div class="notfound">
          <div class="notfound-404">
            <h1>Oops!</h1>
          </div>
          <h2>500 - Server error</h2>
          <p>
            The page you are looking for might have been removed had its name changed or is temporarily unavailable.
          </p>
          <router-link :to="{ path: $store.state.settings.redirect }">Go To Homepage</router-link>
        </div>
      </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
#notfound {
  position: relative;
  height: 100vh;
}

#notfound .notfound {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

.notfound {
  max-width: 410px;
  width: 100%;
  text-align: center;
}

.notfound .notfound-404 {
  height: 20rem;
  position: relative;
  z-index: -1;
}

.notfound .notfound-404 h1 {
  font-family: 'Poppins', sans-serif;
  font-size: 230px;
  margin: 0;
  font-weight: 900;
  position: absolute;
  left: 50%;
  -webkit-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  transform: translateX(-50%);
  background: url('~@/assets/images/bg-error.jpg') no-repeat;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-size: cover;
  background-position: center;
}

.notfound h2 {
  font-family: 'Poppins', sans-serif;
  color: #000;
  font-size: 24px;
  font-weight: 700;
  text-transform: uppercase;
  margin-top: 0;
}

.notfound p {
  font-family: 'Poppins', sans-serif;
  color: #000;
  font-size: 14px;
  font-weight: 400;
  margin-bottom: 20px;
  margin-top: 0;
}

.notfound a {
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  text-decoration: none;
  text-transform: uppercase;
  background: #0046d5;
  display: inline-block;
  padding: 15px 30px;
  border-radius: 40px;
  color: #fff;
  font-weight: 700;
  -webkit-box-shadow: 0px 4px 15px -5px #0046d5;
  box-shadow: 0px 4px 15px -5px #0046d5;
}

@media only screen and (max-width: 767px) {
  .notfound .notfound-404 {
    height: 16rem;
  }
  .notfound .notfound-404 h1 {
    font-size: 112px;
  }
}
</style>
