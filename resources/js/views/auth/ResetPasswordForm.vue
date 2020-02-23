<template>
  <el-container
    class="blue-grey lighten-5 h-screen justify-center items-center"
  >
    <el-row>
      <el-col :xs="24" :sm="24" :lg="24" :xl="24">
        <el-card>
          <div slot="header" class="text-center">
            {{ $t('auth.reset_password') }}
          </div>
          <div>
            <el-form ref="resetForm" :model="form" status-icon :rules="rules">
              <el-form-item
                :label="$t('auth.login.email')"
                prop="email"
                required
              >
                <el-input v-model="form.email" type="text" autocomplete="off" />
              </el-form-item>
              <el-form-item
                data-generator="password"
                required
                :label="$t('table.user.password')"
                :error="errors.password ? errors.password[0] : ''"
                prop="password"
              >
                <el-input
                  v-model="form.password"
                  show-password
                  type="password"
                />
              </el-form-item>
              <el-form-item
                data-generator="password_confirmation"
                required
                :label="$t('table.user.password_confirmation')"
                prop="password_confirmation"
              >
                <el-input
                  v-model="form.password_confirmation"
                  show-password
                  type="password"
                />
              </el-form-item>
              <el-form-item class="text-center">
                <el-button
                  v-loading.fullscreen.lock="loadingResetPassword"
                  type="primary"
                  icon="el-icon-check"
                  circle
                  @click="resetPassword('resetForm')"
                />
              </el-form-item>
            </el-form>
          </div>
        </el-card>
      </el-col>
    </el-row>
  </el-container>
</template>

<script>
import { callResetPassword } from '@/api/auth';
import { validEmail } from '@/utils/validate';

export default {
  data() {
    const password = (rule, value, cb) => {
      if (value === '') {
        cb(
          new Error(
            this.$t('validation.required', {
              attribute: this.$t('table.user.password'),
            })
          )
        );
      } else {
        if (this.form.password_confirmation !== '') {
          this.$refs.resetForm.validateField('password_confirmation');
        }
        cb();
      }
    };
    const passwordConfirm = (rule, value, cb) => {
      if (value === '') {
        cb(
          new Error(
            this.$t('validation.required', {
              attribute: this.$t('table.user.password_confirmation'),
            })
          )
        );
      } else if (value !== this.form.password) {
        cb(
          new Error(
            this.$t('validation.confirmed', {
              attribute: this.$t('table.user.password_confirmation'),
            })
          )
        );
      } else {
        cb();
      }
    };
    return {
      form: {
        token: this.$route.params.token,
        email: '',
        password: '',
        password_confirmation: '',
      },
      loadingResetPassword: false,
      rules: {
        email: [
          {
            validator: (rule, value, cb) => {
              if (!value) {
                cb(
                  new Error(
                    this.$t('validation.required', {
                      attribute: this.$t('table.user.email'),
                    })
                  )
                );
              } else if (!validEmail(value)) {
                cb(
                  new Error(
                    this.$t('validation.email', {
                      attribute: this.$t('table.user.email'),
                    })
                  )
                );
              } else {
                cb();
              }
            },
            trigger: ['blur', 'change'],
          },
        ],
        password: [{ validator: password, trigger: ['change', 'blur'] }],
        password_confirmation: [
          { validator: passwordConfirm, trigger: ['change', 'blur'] },
        ],
      },
    };
  },
  methods: {
    resetPassword(nameForm) {
      this.$refs[nameForm].validate(valid => {
        if (!valid) {
          return false;
        }
        this.loadingResetPassword = true;
        callResetPassword(this.form)
          .then(res => {
            this.$router.push({ name: 'login' });
            this.$message({
              showClose: true,
              message: res.data.message,
              type: 'success',
            });
            this.loadingResetPassword = false;
          })
          .catch(() => {
            this.loadingResetPassword = false;
          });
      });
    },
  },
};
</script>
