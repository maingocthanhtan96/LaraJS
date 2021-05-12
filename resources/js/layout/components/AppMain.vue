<template>
  <section class="app-main">
    <transition :name="transitionName" mode="out-in">
      <keep-alive :include="cachedViews">
        <router-view :key="key" />
      </keep-alive>
    </transition>
  </section>
</template>

<script>
export default {
  name: 'AppMain',
  data() {
    return {
      transitionName: this.$store.state.settings.routerTransitionTo,
    };
  },
  computed: {
    cachedViews() {
      return this.$store.getters.cachedViews;
    },
    key() {
      return this.$route.fullPath;
    },
  },
  mounted() {
    const settings = this.$store.state.settings;
    if (settings.routerTransitionFrom) {
      this.$watch('$route', (to, from) => {
        const toDepth = to.path.split('/').length;
        const fromDepth = from.path.split('/').length;
        this.transitionName = toDepth < fromDepth ? settings.routerTransitionTo : settings.routerTransitionFrom;
      });
    }
  },
};
</script>

<style lang="scss" scoped>
.app-main {
  /* 50= navbar  50  */
  min-height: calc(100vh - 8rem);
  width: 100%;
  position: relative;
  overflow: hidden;
}

.fixed-header + .app-main {
  padding-top: 5rem;
}

.hasTagsView {
  //.app-main {
  //  /* 84 = navbar + tags-view = 50 + 30 */
  //  min-height: calc(100vh - 8rem);
  //}

  .fixed-header + .app-main {
    padding-top: 84px;
  }
}
</style>
