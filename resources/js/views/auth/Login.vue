<template>
  <el-container
    class="blue-grey lighten-5 h-screen justify-center items-center"
  >
    <el-row>
      <el-col :span="24">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <div class="flex justify-center items-center relative">
              <img
                :src="require('@/assets/images/logo/logo-tanmnt.png')"
                width="200"
              />
              <el-dropdown
                class="language absolute right-0 top-0"
                trigger="click"
                @command="handleCommand"
              >
                <span class="el-dropdown-link">
                  <svg-icon icon-class="language" class="text-4xl" />
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item
                    :class="{
                      'bg-blue-400 text-white font-bold': lang === 'vn',
                    }"
                    icon="flag-icon flag-icon-vn"
                    command="vn"
                  >
                    Việt Nam
                  </el-dropdown-item>
                  <el-dropdown-item
                    :class="{
                      'bg-blue-400 text-white font-bold': lang === 'en',
                    }"
                    icon="flag-icon flag-icon-my"
                    command="en"
                  >
                    English
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </div>
          </div>
          <el-form
            ref="login"
            :model="form"
            status-icon
            :rules="rules"
            label-width="120px"
            label-position="left"
          >
            <el-form-item :label="$t('auth.login.email')" prop="email">
              <el-input v-model="form.email" type="text" autocomplete="on" />
            </el-form-item>
            <el-form-item :label="$t('auth.login.password')" prop="password">
              <el-input
                v-model="form.password"
                type="password"
                show-password
                autocomplete="off"
                @keyup.enter.native="login"
              />
            </el-form-item>
          </el-form>
          <el-row>
            <el-col :span="24" class="mb-5">
              <el-button
                type="primary"
                :loading="loading"
                class="w-full"
                @click.prevent="login"
              >
                {{ $t('auth.login.login') }}
              </el-button>
            </el-col>
            <el-col :span="12">
              <el-checkbox>{{ $t('auth.login.remember') }}</el-checkbox>
            </el-col>
            <el-col :span="12" class="text-right">
              <router-link :to="{ name: 'reset-password' }" class="text-black">
                {{ $t('auth.login.forgot_password') }}
              </router-link>
            </el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
  </el-container>
</template>

<script>
import { mapGetters } from 'vuex';
import { SET_LANG, LOGIN } from '@/store/muation-types';

export default {
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      loading: false,
      languages: [
        {
          value: 'vn',
          title: 'Việt Nam',
        },
        {
          value: 'en',
          title: 'English',
        },
      ],
      redirect: undefined,
    };
  },
  computed: {
    rules() {
      return {
        email: [
          {
            required: true,
            message: this.$t('auth.error.email'),
            trigger: ['change', 'blur'],
          },
          {
            type: 'email',
            message: this.$t('auth.error.email_valid'),
            trigger: ['change', 'blur'],
          },
        ],
        password: [
          {
            required: true,
            message: this.$t('auth.error.password'),
            trigger: ['change', 'blur'],
          },
        ],
      };
    },
    ...mapGetters(['user', 'lang']),
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect;
      },
      immediate: true,
    },
  },
  methods: {
    handleCommand(command) {
      if (command === 'vn' || command === 'en') {
        this.$store.dispatch(`lang/${SET_LANG}`, command);
      }
    },
    login() {
      this.loading = true;
      this.$refs['login'].validate(valid => {
        if (valid) {
          this.$store
            .dispatch(`user/${LOGIN}`, this.form)
            .then(() => {
              this.loading = false;
              this.$router.push({
                path: this.redirect || this.$store.state.settings.redirect,
              });
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          this.loading = false;
          return false;
        }
      });
    },
  },
};
</script>
