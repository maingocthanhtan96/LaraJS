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
import { routes } from '@fe/router';

export default {
  name: 'AppMain',
  data() {
    return {
      transitionName: this.$store.state.settings.routerTransitionTo,
    };
  },
  computed: {
    cachedViews() {
      const viewCache = [];
      this.routeChild(routes, viewCache);
      return viewCache;
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
  methods: {
    routeChild(routes, viewCache) {
      for (const route of routes) {
        if (!route?.meta?.noCache) {
          if (route.children) {
            this.routeChild(route.children, viewCache);
          } else {
            const name = route.name;
            name && !viewCache.includes(name) && viewCache.push(name);
          }
        }
      }
      return viewCache;
    },
  },
};
</script>

<style lang="scss" scoped></style>
