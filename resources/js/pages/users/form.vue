<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header">
          <template v-if="$route.params.id">
            {{ $t('table.users.form_edit') }}
          </template>
          <template v-else>
            {{ $t('table.users.form_create') }}
          </template>
        </div>
        <el-form ref="users" :model="form" :rules="rules" status-icon>
          <el-form-item :label="$t('table.users.name')" prop="name">
            <el-input v-model="form.name" autofocus />
          </el-form-item>
          <el-form-item :error="errors.email ? errors.email[0] + '' : ''" :label="$t('table.users.email')" prop="email">
            <el-input v-model="form.email" />
          </el-form-item>
          <el-form-item :label="$t('table.users.role')" prop="role_id">
            <el-select v-model="form.role_id" placeholder="Role" class="w-full">
              <el-option v-for="role in rolesList" :key="'role_' + role.id" :label="role.name" :value="role.id" />
            </el-select>
          </el-form-item>
          <el-form-item v-if="!$route.params.id" required :label="$t('table.users.password')" prop="password">
            <el-input v-model="form.password" show-password type="password" />
          </el-form-item>
          <el-form-item v-if="!$route.params.id" required :label="$t('table.users.password_confirm')" prop="password_confirm">
            <el-input v-model="form.password_confirm" show-password type="password" />
          </el-form-item>
          <el-form-item class="flex justify-center">
            <template v-if="$route.params.id">
              <el-button :loading="loading" plain type="primary" icon="fa fa-edit mr-2" @click="update('users')">
                {{ $t('table.users.form_edit') }}
              </el-button>
            </template>
            <template v-else>
              <el-button :loading="loading" plain type="success" icon="fa fa-plus mr-2" @click="store('users')">
                {{ $t('table.users.form_create') }}
              </el-button>
            </template>
          </el-form-item>
        </el-form>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import { store, roles, edit, update } from '@/api/users';
import { validEmail } from '@/utils/validate';

export default {
  data() {
    const password = (rule, value, cb) => {
      if (value === '') {
        cb(new Error(this.$t('validation.required', { attribute: this.$t('table.users.password') })));
      } else {
        if (this.form.password_confirm !== '') {
          this.$refs.users.validateField('password_confirm');
        }
        cb();
      }
    };
    const passwordConfirm = (rule, value, cb) => {
      if (value === '') {
        cb(new Error(this.$t('validation.required', { attribute: this.$t('table.users.password_confirm') })));
      } else if (value !== this.form.password) {
        cb(new Error(this.$t('validation.confirmed', { attribute: this.$t('table.users.password_confirm') })));
      } else {
        cb();
      }
    };
    return {
      loading: false,
      rolesList: [],
      form: {
        name: '',
        email: '',
        role_id: '',
        password: '',
        password_confirm: '',
      },
      rules: {
        name: [
          {
            validator: (rule, value, cb) => {
              value ? cb() : cb(new Error(this.$t('validation.required', { attribute: this.$t('table.users.name') })));
            },
            trigger: 'blur',
          },
        ],
        email: [
          {
            validator: (rule, value, cb) => {
              if (!value) {
                cb(new Error(this.$t('validation.required', { attribute: this.$t('table.users.email') })));
              } else if (!validEmail(value)) {
                cb(new Error(this.$t('validation.email', { attribute: this.$t('table.users.email') })));
              } else {
                cb();
              }
            },
            trigger: ['blur', 'change'],
          },
        ],
        role_id: [
          {
            validator: (rule, value, cb) => {
              value ? cb() : cb(new Error(this.$t('validation.required', { attribute: this.$t('table.users.role') })));
            },
            trigger: 'change',
          },
        ],
        password: [
          { validator: password, trigger: ['change', 'blur'] },
        ],
        password_confirm: [
          { validator: passwordConfirm, trigger: ['change', 'blur'] },
        ],
      },
    };
  },
  watch: {
  },
  mounted() {
    this.roles();

    const { id } = this.$route.params;
    if (id) {
      edit(id)
        .then(res => {
          this.form = res.data.data;
        });
    }
  },
  methods: {
    store(users) {
      this.loading = true;
      this.$refs[users].validate(valid => {
        if (valid) {
          store(this.form)
            .then(res => {
              this.$message({
                showClose: true,
                message: this.$t('messages.create'),
                type: 'success',
              });
              this.$refs[users].resetFields();
            })
            .catch(err => {
              console.log(err);
            });
        } else {
          return false;
        }
      });
      this.loading = false;
    },
    roles() {
      roles().then(err => {
        this.rolesList = err.data.data;
      });
    },
    update(users) {
      this.$refs[users].validate(valid => {
        if (valid) {
          delete this.form.password;
          update(this.form, this.$route.params.id)
            .then(res => {
              this.$message({
                showClose: true,
                message: this.$t('messages.update'),
                type: 'success',
              });
              this.$router.push({ name: 'users' });
            }).catch(err => {
              console.log(err);
            });
        } else {
          return false;
        }
      });
      this.loading = false;
    },
  },
};
</script>
