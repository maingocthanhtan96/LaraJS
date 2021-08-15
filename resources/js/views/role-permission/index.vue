<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header">
          <h3>{{ $t('route.role_permission') }}</h3>
        </div>
        <div class="tw-text-right tw-mb-8">
          <el-button type="primary" icon="el-icon-plus" @click="showCreateRole()">Role</el-button>
        </div>
        <div>
          <!-- table role -->
          <el-table v-loading="loading" highlight-current-row fit border :data="roles">
            <el-table-column type="index" align="center" :label="$t('table.rolePermission.id')" width="50px" />
            <el-table-column align="center" :label="$t('table.rolePermission.name')">
              <template slot-scope="{ row }">
                {{ row.name }}
              </template>
            </el-table-column>
            <el-table-column header-align="center" :label="$t('table.rolePermission.description')">
              <template slot-scope="{ row }">
                {{ row.description }}
              </template>
            </el-table-column>
            <el-table-column v-if="checkPermission(['manage permission'])" align="center" label="Actions">
              <template slot-scope="{ row }">
                <div v-if="row.name === superAdmin && checkRole([superAdmin])">
                  <el-button
                    type="success"
                    round
                    icon="el-icon-view"
                    size="small"
                    @click="handleEditRolePermissions(row.id)"
                  />
                </div>
                <div v-if="row.name !== superAdmin" class="tw-flex tw-flex-wrap tw-justify-center">
                  <el-button
                    type="primary"
                    icon="el-icon-edit"
                    size="small"
                    @click="handleEditRolePermissions(row.id)"
                  ></el-button>
                  <el-button
                    type="danger"
                    class="tw-ml-0 md:tw-ml-4"
                    icon="el-icon-delete"
                    size="small"
                    @click="handleDeleteRole(row.id, row.name)"
                  />
                </div>
              </template>
            </el-table-column>
          </el-table>
          <!-- end table role -->
          <!-- table permission -->
          <div class="tw-my-8 tw-text-right">
            <el-button type="primary" icon="el-icon-plus" @click="showCreatePermission()">Permission</el-button>
          </div>
          <el-input
            v-model="query.keyword"
            :placeholder="$t('table.rolePermission.name')"
            class="tw-w-64 tw-mb-4"
            @keyup.enter.native="getPermissions"
          />
          <el-table v-loading="loading" highlight-current-row fit border :data="permissions">
            <el-table-column type="index" align="center" :label="$t('table.rolePermission.id')" width="50px" />
            <el-table-column align="center" :label="$t('table.rolePermission.name')">
              <template slot-scope="{ row }">
                {{ row.name }}
              </template>
            </el-table-column>
            <el-table-column header-align="center" :label="$t('table.rolePermission.description')">
              <template slot-scope="{ row }">
                {{ row.description }}
              </template>
            </el-table-column>
            <el-table-column v-if="checkPermission(['manage permission'])" align="center" label="Actions">
              <template
                v-if="row.name !== 'manage permission'"
                v-permission="['manage permission']"
                slot-scope="{ row }"
              >
                <div class="tw-flex tw-flex-wrap tw-justify-center">
                  <el-button type="primary" icon="el-icon-edit" size="small" @click="handleEditPermissions(row.id)" />
                  <el-button
                    type="danger"
                    class="tw-ml-0 md:tw-ml-4"
                    icon="el-icon-delete"
                    size="small"
                    @click="handleDeletePermission(row.id, row.name)"
                  />
                </div>
              </template>
            </el-table-column>
          </el-table>
          <pagination
            v-show="total > 0"
            :total="total"
            :page.sync="query.page"
            :limit.sync="query.limit"
            @pagination="getPermissions"
          />
          <!-- end table permission -->
          <!--update role by permission-->
          <el-dialog
            :title="'[' + currentRole.name + '] Edit Permission'"
            :visible.sync="dialogUpdateRoleVisible"
            center
          >
            <div>
              <el-form ref="formRole" :model="formRole" :rules="roleRules" label-width="110px" label-position="left">
                <el-form-item required label="Role name" prop="name">
                  <el-input v-model="formRole.name" autofocus />
                </el-form-item>
                <el-form-item label="Description" prop="description">
                  <el-input v-model="formRole.description" type="textarea" />
                </el-form-item>
              </el-form>
            </div>
            <div class="tw-flex tw-justify-between tw-items-start">
              <el-form class="tw-w-6/12" :model="currentRole" label-width="80px" label-position="top">
                <el-form-item label="Menus">
                  <el-tree
                    ref="menuPermissions"
                    :data="routesData"
                    :props="permissionProps"
                    :default-checked-keys="permissionName(roleMenuPermissions)"
                    show-checkbox
                    node-key="name"
                    class="permission-tree"
                  />
                </el-form-item>
              </el-form>
              <el-form class="tw-w-6/12" :model="currentRole" label-width="80px" label-position="top">
                <el-form-item label="Permissions">
                  <el-tree
                    ref="otherPermissions"
                    :data="otherPermissions"
                    :default-checked-keys="permissionKeys(roleOtherPermissions)"
                    :props="permissionProps"
                    show-checkbox
                    node-key="id"
                    class="permission-tree"
                  />
                </el-form-item>
              </el-form>
            </div>
            <span slot="footer" class="dialog-footer">
              <el-button @click="dialogUpdateRoleVisible = false">Cancel</el-button>
              <el-button
                v-if="currentRole.name !== superAdmin"
                type="primary"
                icon="el-icon-check"
                @click="updateRolePermission('formRole')"
              >
                {{ $t('button.update') }}
              </el-button>
            </span>
          </el-dialog>
          <!--end update role permission-->
          <!-- create role -->
          <el-dialog title="Create role" :visible.sync="dialogCreateRoleVisible" center>
            <div class="tw-flex tw-justify-between tw-items-start">
              <el-form ref="formRole" :model="formRole" :rules="roleRules" label-width="110px" label-position="left">
                <el-form-item required label="Role name" :error="errors.name && errors.name[0]" prop="name">
                  <el-input v-model="formRole.name" autofocus placeholder="Ex: admin" />
                </el-form-item>
                <el-form-item label="Description" prop="description">
                  <el-input v-model="formRole.description" type="textarea" />
                </el-form-item>
              </el-form>
            </div>
            <span slot="footer" class="dialog-footer">
              <el-button @click="dialogCreateRoleVisible = false">Cancel</el-button>
              <el-button type="primary" icon="el-icon-plus" @click="createRole('formRole')">
                {{ $t('button.create') }}
              </el-button>
            </span>
          </el-dialog>
          <!-- end create role -->
          <!-- create permission -->
          <el-dialog title="Create permission" :visible.sync="dialogCreatePermissionVisible" center>
            <div>
              <el-form
                ref="formPermission"
                :model="formPermission"
                :rules="permissionRules"
                label-width="110px"
                label-position="left"
              >
                <el-form-item required label="Permission" :error="errors.name && errors.name[0]" prop="name">
                  <el-input v-model="formPermission.name" autofocus placeholder="Ex: role_permission" />
                </el-form-item>
                <el-form-item label="Description" prop="description">
                  <el-input v-model="formPermission.description" type="textarea" />
                </el-form-item>
              </el-form>
            </div>
            <span slot="footer" class="dialog-footer">
              <el-button @click="dialogCreatePermissionVisible = false">Cancel</el-button>
              <el-button
                v-if="+permissionId === 0"
                type="primary"
                icon="el-icon-plus"
                @click="createPermission('formPermission')"
              >
                {{ $t('button.create') }}
              </el-button>
              <el-button v-else type="primary" icon="el-icon-check" @click="updatePermission('formPermission')">
                {{ $t('button.update') }}
              </el-button>
            </span>
          </el-dialog>
          <!-- end create permission -->
        </div>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import path from 'path';
import RoleResource from '@/api/v1/role';
import PermissionResource from '@/api/v1/permission';
import permission from '@/directive/permission';
import role from '@/directive/role';
import checkPermission from '@/utils/permission'; // Permission checking
import checkRole from '@/utils/role'; // Permission checking
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import { asyncRouterMap } from '@/router';

const permissionResource = new PermissionResource();
const roleResource = new RoleResource();

export default {
  components: { Pagination },
  directives: { permission, role },
  data() {
    return {
      loading: false,
      loadingPermission: false,
      currentRoleId: 0,
      permissionId: 0,
      dialogUpdateRoleVisible: false,
      dialogCreateRoleVisible: false,
      dialogCreatePermissionVisible: false,
      routes: [],
      roles: [],
      permissions: [],
      menuPermissions: [],
      otherPermissions: [],
      permissionProps: {
        children: 'children',
        label: 'name',
        disabled: 'disabled',
      },
      formRole: {
        name: '',
        description: '',
      },
      formPermission: {
        name: '',
        description: '',
      },
      query: {
        page: 1,
        limit: 25,
        keyword: '',
      },
      total: 0,
    };
  },
  computed: {
    superAdmin() {
      return this.$store.state.settings.superAdmin;
    },
    currentRole() {
      const role = this.roles.find(role => role.id === this.currentRoleId);
      if (role === undefined) {
        return { name: '', permissions: [] };
      }
      return role;
    },
    rolePermissions() {
      return this.classifyPermissions(this.currentRole.permissions).all;
    },
    roleMenuPermissions() {
      return this.classifyPermissions(this.currentRole.permissions).menu;
    },
    roleOtherPermissions() {
      return this.classifyPermissions(this.currentRole.permissions).other;
    },
    routesData() {
      return this.routes;
    },
    roleRules() {
      return {
        name: [
          {
            validator: (rule, value, callback) => {
              if (!value) {
                return callback(new Error(this.$t('validation.required', { attribute: 'Role' })));
              } else {
                const checkExist = this.roles.some(val => val.name === value && val.id !== this.currentRoleId);
                if (checkExist) {
                  return callback(new Error('Role exist!'));
                } else {
                  callback();
                }
              }
            },
            trigger: ['blur', 'change'],
          },
        ],
      };
    },
    permissionRules() {
      return {
        name: [
          {
            validator: (rule, value, callback) => {
              if (!value) {
                return callback(new Error(this.$t('validation.required', { attribute: 'Permission' })));
              } else {
                const checkExist = this.permissions.some(val => val.name === value && val.id !== this.permissionId);
                if (checkExist) {
                  return callback(new Error('Permission exist!'));
                } else {
                  callback();
                }
              }
            },
            trigger: ['blur', 'change'],
          },
        ],
      };
    },
  },
  created() {
    this.getRoles();
    this.getPermissions();
    this.getRoutes();
  },
  methods: {
    checkPermission,
    checkRole,
    async getRoutes() {
      this.routes = this.generateRoutes(asyncRouterMap);
    },
    generateRoutes(routes, basePath = '/') {
      const res = [];
      for (let route of routes) {
        // skip some routez
        if (route.hidden) {
          continue;
        }
        const onlyOneShowingChild = this.onlyOneShowingChild(route.children, route);
        if (route.children && onlyOneShowingChild && !route.alwaysShow) {
          route = onlyOneShowingChild;
        }
        const data = {
          path: path.resolve(basePath, route.path),
          name: route.meta && route.meta.title,
        };
        // recursive child routes
        if (route.children) {
          data.children = this.generateRoutes(route.children, data.path);
        }
        res.push(data);
      }
      return res;
    },
    getRoles() {
      this.loading = true;
      roleResource.list().then(res => {
        this.roles = res.data.data;
        this.loading = false;
      });
    },
    getPermissions() {
      this.loadingPermission = true;
      permissionResource.list(this.query).then(res => {
        const { data } = res.data;
        const { all, menu, other } = this.classifyPermissions(data);
        this.total = res.data.meta.total;
        this.permissions = all;
        this.menuPermissions = menu;
        this.otherPermissions = other;
        this.loadingPermission = false;
      });
    },
    classifyPermissions(permissions) {
      const all = [];
      const menu = [];
      const other = [];
      permissions.forEach(permission => {
        const permissionName = permission.name;
        all.push(permission);
        if (permissionName.startsWith('view menu')) {
          menu.push(this.normalizeMenuPermission(permission));
        } else {
          other.push(this.normalizePermission(permission));
        }
      });
      return { all, menu, other };
    },
    normalizeMenuPermission(permission) {
      return { id: permission.id, name: permission.name.substring(10) };
    },

    normalizePermission(permission) {
      return {
        id: permission.id,
        name: permission.name,
        disabled: checkRole([this.superAdmin]) ? false : permission.name === 'manage permission',
      };
    },
    permissionKeys(permissions) {
      return permissions.map(permission => permission.id);
    },
    permissionName(permissions) {
      return permissions.map(permission => permission.name);
    },
    handleEditRolePermissions(id) {
      this.resetFormRole();
      this.dialogUpdateRoleVisible = true;
      this.currentRoleId = id;
      this.formRole = Object.assign({}, this.currentRole);
      this.$nextTick(() => {
        this.$refs.menuPermissions.setCheckedKeys(this.permissionName(this.roleMenuPermissions));
        this.$refs.otherPermissions.setCheckedKeys(this.permissionKeys(this.roleOtherPermissions));
      });
    },
    updateRolePermission(formName) {
      const checkedMenu = this.$refs.menuPermissions.getCheckedKeys();
      const checkedOther = this.$refs.otherPermissions.getCheckedKeys();
      const permissions = {
        menu: checkedMenu,
        other: checkedOther,
      };
      this.$refs[formName].validate(valid => {
        if (valid) {
          // const checkedPermissions = checkedMenu.concat(checkedOther);
          roleResource.update(this.currentRole.id, { permissions, role: this.formRole }).then(res => {
            this.$message({
              message: 'Role ' + this.$t('messages.update'),
              type: 'success',
              duration: 5 * 1000,
            });
            this.dialogUpdateRoleVisible = false;
            this.getRoles();
            this.getPermissions();
          });
        }
      });
    },
    showCreateRole() {
      this.resetFormRole();
      this.dialogCreateRoleVisible = true;
    },
    createRole(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          roleResource.store(this.formRole).then(res => {
            const { data } = res.data;
            this.roles.push(data);
            // notification
            this.$notify({
              title: this.$t('messages.update'),
              dangerouslyUseHTMLString: true,
              message: `
                  <div>Role name: ${this.formRole.name}</div>
                  <div>Description: ${this.formRole.description}</div>
                `,
              type: 'success',
            });
            this.$refs[formName].resetFields();
          });
        }
      });
    },
    handleDeleteRole(id, name) {
      this.$confirm(this.$t('messages.delete_confirm', { attribute: `[${name}]` }), this.$t('messages.warning'), {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        roleResource.destroy(id).then(res => {
          const index = this.roles.findIndex(val => val.id === id);
          this.roles.splice(index, 1);
          this.$notify({
            title: 'Success',
            message: this.$t('messages.delete'),
            type: 'success',
          });
        });
      });
    },
    showCreatePermission() {
      this.dialogCreatePermissionVisible = true;
      this.permissionId = 0;
      this.resetFormPermission();
    },
    createPermission(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          permissionResource.store(this.formPermission).then(() => {
            // notification
            this.$notify({
              title: this.$t('messages.update'),
              dangerouslyUseHTMLString: true,
              message: `
                  <div>Role name: ${this.formPermission.name}</div>
                  <div>Description: ${this.formPermission.description}</div>
                `,
              type: 'success',
            });
            this.getPermissions();
            this.$refs[formName].resetFields();
          });
        }
      });
    },
    handleDeletePermission(id, name) {
      this.$confirm(this.$t('messages.delete_confirm', { attribute: `[${name}]` }), this.$t('messages.warning'), {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        permissionResource.destroy(id).then(() => {
          this.$notify({
            title: 'Success',
            message: this.$t('messages.delete'),
            type: 'success',
          });
          this.getPermissions();
        });
      });
    },
    handleEditPermissions(id) {
      this.permissionId = id;
      this.dialogCreatePermissionVisible = true;
      const permission = this.permissions.find(val => val.id === id);
      this.formPermission = Object.assign({}, permission);
    },
    updatePermission(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          permissionResource.update(this.permissionId, this.formPermission).then(() => {
            this.$message({
              message: 'Permissions ' + this.$t('messages.update'),
              type: 'success',
              duration: 5 * 1000,
            });
            this.dialogCreatePermissionVisible = false;
            this.getPermissions();
          });
        }
      });
    },
    // reference: src/layout/components/Sidebar/SidebarItem.vue
    onlyOneShowingChild(children = [], parent) {
      let onlyOneChild = null;
      const showingChildren = children.filter(item => !item.hidden);

      // When there is only one child route, the child route is displayed by default
      if (showingChildren.length === 1) {
        onlyOneChild = showingChildren[0];
        onlyOneChild.path = path.resolve(parent.path, onlyOneChild.path);
        return onlyOneChild;
      }

      // Show parent if there are no child route to display
      if (showingChildren.length === 0) {
        onlyOneChild = { ...parent, path: '', noShowingChildren: true };
        return onlyOneChild;
      }

      return false;
    },
    resetFormRole() {
      this.formRole = {
        name: '',
        description: '',
      };
    },
    resetFormPermission() {
      this.formPermission = {
        name: '',
        description: '',
      };
    },
  },
};
</script>
