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
    if (this.$store.state.settings.routerTransitionFrom) {
      this.$watch('$route', (to, from) => {
        const toDepth = to.path.split('/').length;
        const fromDepth = from.path.split('/').length;
        this.transitionName =
          toDepth < fromDepth
            ? this.$store.state.settings.routerTransitionTo
            : this.$store.state.settings.routerTransitionFrom;
      });
    }
  },
};
</script>

<style lang="scss" scoped>

</style>
