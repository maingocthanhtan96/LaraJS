<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device === 'mobile' && sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <sidebar class="sidebar-container" />
    <div :class="{ hasTagsView: needTagsView }" class="main-container">
      <div :class="{ 'fixed-header': fixedHeader }">
        <navbar />
        <tags-view v-if="needTagsView" />
      </div>
      <app-main />
      <footer-main />
    </div>
    <!--<el-tooltip> to show the helptext -->
    <el-tooltip placement="top" :content="$t('common.back_to_top')">
      <back-to-top
        :custom-style="myBackToTopStyle"
        :visibility-height="200"
        :back-position="0"
        transition-name="fade"
      />
    </el-tooltip>
  </div>
</template>

<script>
import BackToTop from '@/components/BackToTop';
import { AppMain, Navbar, Sidebar } from './components';
const TagsView = () => import('./components/TagsView');
import FooterMain from './components/FooterMain';
import ResizeMixin from './mixin/ResizeHandler';
import { mapState } from 'vuex';

export default {
  name: 'Layout',
  components: {
    AppMain,
    Navbar,
    Sidebar,
    TagsView,
    FooterMain,
    BackToTop,
  },
  mixins: [ResizeMixin],
  data() {
    return {
      myBackToTopStyle: {
        right: '10px',
        bottom: '50px',
        width: '40px',
        height: '40px',
        'border-radius': '4px',
        'line-height': '45px', // Please keep consistent with height to center vertically
        background: '#e7eaf1', // The background color of the button,
        display: 'flex',
        'justify-content': 'center',
        'align-items': 'center',
      },
    };
  },
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
      needTagsView: state => state.settings.tagsView,
      fixedHeader: state => state.settings.fixedHeader,
    }),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile',
      };
    },
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~@/styles/mixin.scss';
@import '~@/styles/variables.scss';

.app-wrapper {
  @include clearfix;
  position: relative;
  height: 100%;
  width: 100%;

  &.mobile.openSidebar {
    position: fixed;
    top: 0;
  }
}

.drawer-bg {
  background: #000;
  opacity: 0.3;
  width: 100%;
  top: 0;
  height: 100%;
  position: absolute;
  z-index: 2002;
}

.fixed-header {
  position: fixed;
  top: 0;
  right: 0;
  z-index: 2001;
  width: calc(100% - #{$sideBarWidth});
  transition: width 0.28s;
}

.hideSidebar .fixed-header {
  width: calc(100% - 54px);
}

.mobile .fixed-header {
  width: 100%;
}
</style>
