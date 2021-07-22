<template>
  <el-container class="tw-blue-grey tw-lighten-5 tw-h-screen tw-justify-center tw-items-center">
    <el-row>
      <el-col :span="24">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <div class="tw-flex tw-justify-center tw-items-center tw-relative">
              <img :src="$store.state.settings.urlLogo" width="200" />
              <lang-select v-if="$store.state.settings.showTrans" class="tw-absolute tw-right-0 tw-top-0" />
            </div>
          </div>
          <el-form ref="login" :model="form" :rules="rules" label-width="120px" label-position="left">
            <el-form-item :label="$t('auth.login.email')" prop="email">
              <el-input v-model="form.email" name="email" type="text" autocomplete="on" />
            </el-form-item>
            <el-form-item :label="$t('auth.login.password')" prop="password">
              <el-input
                v-model="form.password"
                name="password"
                type="password"
                show-password
                autocomplete="off"
                @keyup.enter.native="login"
              />
            </el-form-item>
          </el-form>
          <el-row>
            <el-col :span="24" class="tw-mb-5">
              <el-button type="primary" :loading="loading" class="tw-w-full" @click.prevent="login">
                {{ $t('auth.login.login') }}
              </el-button>
            </el-col>
            <el-col :span="12">
              <el-checkbox v-model="form.remember_me">
                {{ $t('auth.login.remember') }}
              </el-checkbox>
            </el-col>
            <el-col :span="12" class="tw-text-right">
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
import { LOGIN } from '@/store/muation-types';
import LangSelect from '@/components/LangSelect';

export default {
  components: {
    LangSelect,
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember_me: false,
      },
      loading: false,
      redirect: undefined,
      otherQuery: {},
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
      handler: function (route) {
        const query = route.query;
        if (query) {
          this.redirect = query.redirect;
          this.otherQuery = this.getOtherQuery(query);
        }
      },
      immediate: true,
    },
  },
  methods: {
    login() {
      this.loading = true;
      this.$refs['login'].validate(valid => {
        if (valid) {
          this.$store
            .dispatch(`user/${LOGIN}`, this.form)
            .then(() => {
              this.loading = false;
              this.$router.replace(
                {
                  path: this.redirect || this.$store.state.settings.redirect,
                  query: this.otherQuery,
                },
                onAbort => {}
              );
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
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur];
        }
        return acc;
      }, {});
    },
  },
};
</script>

<style scoped></style>
