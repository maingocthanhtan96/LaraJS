<template>
  <el-dropdown
    trigger="click"
    class="international"
    @command="handleSetLanguage"
  >
    <div>
      <svg-icon class-name="tw-text-4xl" icon-class="language" />
    </div>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item
        :disabled="language === 'ja'"
        command="ja"
        icon="flag-icon flag-icon-jp"
      >
        日本語
      </el-dropdown-item>
      <el-dropdown-item
        :disabled="language === 'en'"
        command="en"
        icon="flag-icon flag-icon-um"
      >
        English
      </el-dropdown-item>
      <el-dropdown-item
        :disabled="language === 'vi'"
        command="vi"
        icon="flag-icon flag-icon-vn"
      >
        Tiếng Việt
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import { SET_LANG } from '@/store/muation-types';

export default {
  computed: {
    language() {
      return this.$store.getters.lang;
    },
  },
  methods: {
    handleSetLanguage(lang) {
      this.$store.dispatch(`lang/${SET_LANG}`, lang);
    },
  },
};
</script>
