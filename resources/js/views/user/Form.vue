<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header">
          <template v-if="$route.params.id">
            {{ $t('route.user_edit') }}
          </template>
          <template v-else>
            {{ $t('route.user_create') }}
          </template>
        </div>
        <el-form ref="users" v-loading="loading.form" :model="form" :rules="rules">
          <el-form-item data-generator="name" :label="$t('table.user.name')" required prop="name">
            <el-input v-model="form.name" autofocus />
          </el-form-item>
          <el-form-item
            data-generator="email"
            :error="errors.email && errors.email[0]"
            :label="$t('table.user.email')"
            required
            prop="email"
          >
            <el-input v-model="form.email" />
          </el-form-item>
          <el-form-item
            data-generator="avatar"
            :error="errors.avatar && errors.avatar[0]"
            :label="$t('table.user.avatar')"
            required
            prop="avatar"
          >
            <pan-thumb :image="form.avatar">
              <el-button type="primary" icon="el-icon-upload" @click="imageCropperShow = true" />
            </pan-thumb>
            <image-cropper
              v-show="imageCropperShow"
              :key="imageCropperKey"
              :width="300"
              :height="300"
              :source-img-url="form.avatar"
              lang-type="en"
              @close="close"
              @crop-upload-success="cropSuccess"
            />
          </el-form-item>
          <el-form-item data-generator="role_id" :label="$t('table.user.role')" required prop="role_id">
            <el-select v-model="form.role_id" placeholder="Role" class="w-full">
              <el-option v-for="role in rolesList" :key="'role_' + role.id" :label="role.name" :value="role.id" />
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="!$route.params.id"
            data-generator="password"
            required
            :label="$t('table.user.password')"
            prop="password"
          >
            <el-input v-model="form.password" show-password type="password" />
          </el-form-item>
          <el-form-item
            v-if="!$route.params.id"
            data-generator="password_confirmation"
            required
            :label="$t('table.user.password_confirmation')"
            prop="password_confirmation"
          >
            <el-input v-model="form.password_confirmation" show-password type="password" />
          </el-form-item>
          <!--{{$FROM_ITEM_NOT_DELETE_THIS_LINE$}}-->
          <el-form-item class="tw-flex tw-justify-end">
            <router-link v-slot="{ href, navigate }" :to="{ name: 'User' }" custom>
              <a :href="href" class="el-button el-button--info is-plain" @click="navigate">{{ $t('button.cancel') }}</a>
            </router-link>
            <template v-if="$route.params.id">
              <el-button :loading="loading.button" type="primary" icon="el-icon-edit" @click="() => update('users')">
                {{ $t('button.update') }}
              </el-button>
            </template>
            <template v-else>
              <el-button :loading="loading.button" type="success" icon="el-icon-plus" @click="() => store('users')">
                {{ $t('button.create') }}
              </el-button>
            </template>
          </el-form-item>
        </el-form>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import GlobalForm from '@/plugins/mixins/global-form';
import UserResource from '@/api/v1/user';
import RoleResource from '@/api/v1/role';
import ImageCropper from '@/components/ImageCropper';
import PanThumb from '@/components/PanThumb';
import { validEmail } from '@/utils/validate';
// {{$IMPORT_COMPONENT_NOT_DELETE_THIS_LINE$}}

const userResource = new UserResource();
const roleResource = new RoleResource();

export default {
  components: {
    ImageCropper,
    PanThumb,
    // {{$IMPORT_COMPONENT_NAME_NOT_DELETE_THIS_LINE$}}
  },
  mixins: [GlobalForm],
  data() {
    return {
      rolesList: [],
      form: {
        name: '',
        email: '',
        avatar: '',
        role_id: '',
        password: '',
        password_confirmation: '',
      }, // {{$$}}
      loading: {
        button: false,
        form: false,
      },
      fileOld: '',
      imageCropperShow: false,
      imageCropperKey: 0,
      formData: new FormData(),
      // {{$DATA_NOT_DELETE_THIS_LINE$}}
    };
  },
  computed: {
    // not rename rules
    rules() {
      return {
        name: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('table.user.name'),
            }),
            trigger: 'change',
          },
        ],
        email: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('table.user.email'),
            }),
            trigger: 'change',
          },
          {
            validator: (rule, value, cb) => {
              if (!validEmail(value)) {
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
            trigger: 'change',
          },
        ],
        avatar: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('table.user.avatar'),
            }),
            trigger: 'change',
          },
        ],
        role_id: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('table.user.role'),
            }),
            trigger: 'change',
          },
        ],
        password: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('table.user.password'),
            }),
            trigger: 'change',
          },
          {
            validator: (rule, value, cb) => {
              let messagePassword = '';
              switch (false) {
                case /[a-z]+/.test(value):
                  messagePassword = this.$t('validation.password.lowercase', {
                    attribute: this.$t('table.user.password'),
                    number: 1,
                  });
                  break;
                case /[A-Z]+/.test(value):
                  messagePassword = this.$t('validation.password.uppercase', {
                    attribute: this.$t('table.user.password'),
                    number: 1,
                  });
                  break;
                case /[0-9]+/.test(value):
                  messagePassword = this.$t('validation.password.number', {
                    attribute: this.$t('table.user.password'),
                    number: 1,
                  });
                  break;
                case /[!@#$%^&*]+/.test(value):
                  messagePassword = this.$t('validation.password.symbols', {
                    attribute: this.$t('table.user.password'),
                    number: 1,
                  });
                  break;
              }
              if (messagePassword) {
                return cb(messagePassword);
              }
              if (this.form.password_confirmation !== '') {
                this.$refs.users.validateField('password_confirmation');
              }
              cb();
            },
            trigger: 'change',
          },
          {
            min: 8,
            message: this.$t('validation.min.string', {
              attribute: this.$t('table.user.password'),
              min: 8,
            }),
            trigger: 'change',
          },
        ],
        password_confirmation: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('table.user.password_confirmation'),
            }),
            trigger: 'change',
          },
          {
            validator: (rule, value, cb) => {
              if (value !== this.form.password) {
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
            },
            trigger: ['change'],
          },
        ],
        // {{$RULES_NOT_DELETE_THIS_LINE$}}
      };
    },
  },
  async created() {
    try {
      this.loading.form = true;
      const { id } = this.$route.params;
      // {{$CREATED_NOT_DELETE_THIS_LINE$}}
      const {
        data: { data: roles },
      } = await roleResource.list();
      this.rolesList = roles;
      if (id) {
        const {
          data: { data: user },
        } = await userResource.get(id);
        this.form = user;
      }
      this.loading.form = false;
    } catch (e) {
      this.loading.form = false;
    }
  },
  methods: {
    appendToFormData() {
      this.formData.set('name', this.form.name);
      this.formData.set('email', this.form.email);
      this.formData.set('role_id', this.form.role_id);
      this.formData.set('password', this.form.password);
      this.formData.set('password_confirmation', this.form.password_confirmation);
    },
    store(users) {
      this.$refs[users].validate(async (valid, errors) => {
        if (this.scrollToError(valid, errors)) {
          return;
        }
        this.$confirm(this.$t('common.popup.create'), {
          confirmButtonText: this.$t('button.create'),
          cancelButtonText: this.$t('button.cancel'),
          type: 'warning',
          center: true,
        }).then(async () => {
          try {
            this.loading.button = true;
            this.appendToFormData();
            await userResource.store(this.formData);
            this.$message({
              showClose: true,
              message: this.$t('messages.create'),
              type: 'success',
            });
            this.loading.button = false;
            await this.$router.push({ name: 'User' });
          } catch (e) {
            this.loading.button = false;
          }
        });
      });
    },
    update(users) {
      this.$refs[users].validate(async (valid, errors) => {
        if (this.scrollToError(valid, errors)) {
          return;
        }
        this.$confirm(this.$t('common.popup.update'), {
          confirmButtonText: this.$t('button.update'),
          cancelButtonText: this.$t('button.cancel'),
          type: 'warning',
          center: true,
        }).then(async () => {
          try {
            this.loading.button = true;
            delete this.form.password;
            this.appendToFormData();
            this.formData.set('_method', 'PUT');
            await userResource.update(this.$route.params.id, this.formData);
            this.$message({
              showClose: true,
              message: this.$t('messages.update'),
              type: 'success',
            });
            this.loading.button = false;
            await this.$router.push({ name: 'User' });
          } catch (e) {
            this.loading.button = false;
          }
        });
      });
    },
    cropSuccess(file) {
      this.formData.set('avatar', file.file, file.name);
      this.form.avatar = URL.createObjectURL(file.file);
      this.imageCropperShow = false;
      this.imageCropperKey = this.imageCropperKey + 1;
    },
    close() {
      this.imageCropperShow = false;
      this.form.avatar = '';
      this.formData.set('avatar', this.form.avatar);
    },
  },
};
</script>
