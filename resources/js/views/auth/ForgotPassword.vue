<template>
  <el-container class="tw-blue-grey tw-lighten-5 tw-h-screen tw-justify-center tw-items-center">
    <el-row class="forgot-password">
      <el-col :xs="24" :sm="24" :lg="24" :xl="24">
        <el-card>
          <div slot="header" class="tw-text-center">
            {{ $t('auth.forgot_password') }}
          </div>
          <el-form
            ref="forgotForm"
            :model="form"
            :rules="rules"
            @submit.native.prevent="requestResetPassword('forgotForm')"
          >
            <el-form-item :label="$t('auth.login.email')" prop="email" required>
              <el-input v-model="form.email" type="text" autocomplete="off" />
            </el-form-item>
            <el-form-item class="tw-text-center">
              <el-button
                v-loading.fullscreen.lock="loadingSendEmail"
                type="primary"
                icon="el-icon-message"
                @click="requestResetPassword('forgotForm')"
              />
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>
    </el-row>
  </el-container>
</template>
<script>
import { sendPasswordResetLink } from '@/api/auth';

export default {
  data() {
    return {
      form: {
        email: '',
      },
      loadingSendEmail: false,
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
      };
    },
  },
  methods: {
    requestResetPassword(nameForm) {
      this.$refs[nameForm].validate(valid => {
        if (!valid) {
          return false;
        }
        this.loadingSendEmail = true;
        sendPasswordResetLink(this.form)
          .then(res => {
            this.$message({
              showClose: true,
              message: res.data.message,
              type: 'success',
            });
            this.loadingSendEmail = false;
          })
          .catch(() => {
            this.loadingSendEmail = false;
          });
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.forgot-password {
  width: 50rem;
}
</style>
