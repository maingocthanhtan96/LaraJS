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
            prop="avatar"
          >
            <pan-thumb :image="form.avatar" class-img="bg-white">
              <el-button type="primary" icon="el-icon-upload" @click="imageCropperShow = true" />
            </pan-thumb>
            <image-cropper
              v-show="imageCropperShow"
              :key="imageCropperKey"
              :width="300"
              :height="300"
              url="upload-file/store-avatar"
              :params="{
                fileOld: $route.params.id ? (fileOld ? fileOld : '') : fileOld,
              }"
              field="file"
              lang-type="en"
              @close="close"
              @crop-upload-success="cropSuccess"
              @crop-upload-fail="cropError"
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
            <router-link class="el-button el-button--info is-plain" tag="button" :to="{ name: 'User' }">
              {{ $t('button.cancel') }}
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
        avatar: require('@/assets/images/avatar-default.png').default,
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
      // {{$DATA_NOT_DELETE_THIS_LINE$}}
    };
  },
  computed: {
    // not rename rules
    rules() {
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
            this.$refs.users.validateField('password_confirmation');
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
        name: [
          {
            validator: (rule, value, cb) => {
              value
                ? cb()
                : cb(
                    new Error(
                      this.$t('validation.required', {
                        attribute: this.$t('table.user.name'),
                      })
                    )
                  );
            },
            trigger: 'blur',
          },
        ],
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
        avatar: [
          {
            validator: (rule, value, cb) => {
              value
                ? cb()
                : cb(
                    new Error(
                      this.$t('validation.required', {
                        attribute: this.$t('table.user.avatar'),
                      })
                    )
                  );
            },
            trigger: 'blur',
          },
        ],
        role_id: [
          {
            validator: (rule, value, cb) => {
              value
                ? cb()
                : cb(
                    new Error(
                      this.$t('validation.required', {
                        attribute: this.$t('table.user.role'),
                      })
                    )
                  );
            },
            trigger: 'change',
          },
        ],
        password: [
          { validator: password, trigger: ['change', 'blur'] },
          {
            min: 8,
            message: this.$t('validation.min.string', {
              attribute: this.$t('table.user.password'),
              min: 8,
            }),
            trigger: ['change', 'blur'],
          },
        ],
        password_confirmation: [{ validator: passwordConfirm, trigger: ['change', 'blur'] }],
        // {{$RULES_NOT_DELETE_THIS_LINE$}}
      };
    },
  },
  watch: {},
  mounted() {
    this.roles();
    const { id } = this.$route.params;
    if (id) {
      this.loading.form = false;
      userResource.get(id).then(res => {
        const { data } = res.data;
        this.form = data;
      });
    }
  },
  methods: {
    // {{$METHODS_NOT_DELETE_THIS_LINE$}}
    store(users) {
      // {{$FILE_JSON_STRINGIFY_NOT_DELETE_THIS_LINE$}}
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
          this.loading.button = true;
          await userResource.store(this.form);
          this.$message({
            showClose: true,
            message: this.$t('messages.create'),
            type: 'success',
          });
          this.loading.button = false;
          this.$router.push({ name: 'User' });
        });
      });
    },
    roles() {
      roleResource.list().then(res => {
        this.rolesList = res.data.data;
      });
    },
    update(users) {
      // {{$FILE_JSON_STRINGIFY_NOT_DELETE_THIS_LINE$}}
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
          this.loading.button = true;
          delete this.form.password;
          await userResource.update(this.$route.params.id, this.form);
          this.$message({
            showClose: true,
            message: this.$t('messages.update'),
            type: 'success',
          });
          this.loading.button = false;
          this.$router.push({ name: 'User' });
        });
      });
    },
    cropSuccess(resData) {
      this.imageCropperShow = false;
      this.imageCropperKey = this.imageCropperKey + 1;
      this.form.avatar = resData.data;
      this.fileOld = resData.data;
      this.$message({ message: this.$t('messages.upload'), type: 'success' });
    },
    cropError(error) {
      const err = error.response.data.errors.file[0];
      this.$message({ message: err, type: 'error' });
    },
    close() {
      this.imageCropperShow = false;
    },
  },
};
</script>
