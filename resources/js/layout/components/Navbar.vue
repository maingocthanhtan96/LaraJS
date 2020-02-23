<template>
  <div class="navbar">
    <hamburger
      id="hamburger-container"
      :is-active="sidebar.opened"
      class="hamburger-container d-flex items-center"
      @toggleClick="toggleSideBar"
    />
    <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />
    <div class="right-menu">
      <template v-if="device !== 'mobile'">
        <a
          v-if="checkPermission(['develop'])"
          :href="hrefDeveloper"
          class="mr-4"
        >
          <svg-icon icon-class="api" class="text-4xl" />
        </a>
      </template>
      <el-dropdown
        v-if="$store.state.settings.showTrans"
        class="language pr-2"
        trigger="click"
        @command="handleCommand"
      >
        <span class="el-dropdown-link">
          <svg-icon icon-class="language" class="text-4xl" />
        </span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item
            :class="{ 'bg-blue-400 text-white font-bold': lang === 'vn' }"
            icon="flag-icon flag-icon-vn"
            command="vn"
          >
            Việt Nam
          </el-dropdown-item>
          <el-dropdown-item
            :class="{ 'bg-blue-400 text-white font-bold': lang === 'en' }"
            icon="flag-icon flag-icon-my"
            command="en"
          >
            English
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <el-dropdown
        class="avatar-container right-menu-item hover-effect"
        trigger="click"
      >
        <div class="avatar-wrapper">
          <img
            :src="user.avatar + '?imageView2/1/w/80/h/80'"
            class="user-avatar"
          />
          <i class="el-icon-caret-bottom" />
        </div>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>
            <p @click="logout">Log Out</p>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Breadcrumb from '@/components/Breadcrumb';
import Hamburger from '@/components/Hamburger';
import { LOGOUT, SET_LANG } from '@/store/muation-types';
import checkPermission from '@/utils/permission'; // Permission checking

export default {
  components: {
    Breadcrumb,
    Hamburger,
  },
  data() {
    return {
      hrefDeveloper: `${process.env.MIX_APP_URL}/swagger/index.html`,
    };
  },
  computed: {
    ...mapGetters({
      sidebar: 'sidebar',
      device: 'device',
      user: 'user',
      lang: 'lang',
    }),
  },
  methods: {
    checkPermission,
    handleCommand(command) {
      if (command === 'logout') {
        this.logout();
      } else if (command === 'vn' || command === 'en') {
        this.$store.dispatch(`lang/${SET_LANG}`, command);
      }
    },
    logout() {
      this.$store.dispatch(`user/${LOGOUT}`).then(() => {
        this.$router.push({ name: 'login' });
      });
    },
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar');
    },
  },
};
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 0 !important;

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background 0.3s;
    -webkit-tap-highlight-color: transparent;

    &:hover {
      background: rgba(0, 0, 0, 0.025);
    }
  }

  .breadcrumb-container {
    float: left;
    height: 50px;
  }

  .errLog-container {
    display: inline-block;
    vertical-align: top;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;
    width: auto;
    margin-left: auto;
    display: flex;
    justify-content: flex-end;
    align-items: center;

    &:focus {
      outline: none;
    }

    .language {
      & > .el-dropdown-link {
        display: flex;
        align-items: center;
      }
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #5a5e66;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: background 0.3s;

        &:hover {
          background: rgba(0, 0, 0, 0.025);
        }
      }
    }

    .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 10px;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: -20px;
          top: 25px;
          font-size: 12px;
        }
      }
    }
  }
}
</style>
