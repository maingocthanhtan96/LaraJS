<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header" class="flex justify-end items-center">
          <router-link :to="{name: 'user_create'}" class="pan-btn blue-btn" tag="button">
            <i class="fa fa-plus mr-2" />Create
          </router-link>
        </div>
        <v-server-table
          ref="table_user"
          name="table_user"
          :columns="table.columns"
          :options="table.options"
        >
          <template v-if="loading" slot="afterBody">
            <div v-loading="loading" class="overlay-loader" />
          </template>
          <template slot="id" slot-scope="props">{{ props.index }}</template>
          <!--{{$TEMPLATES_NOT_DELETE_THIS_LINE$}}-->
          <div slot="actions" slot-scope="{row}" class="flex justify-center items-center">
            <router-link :to="{name: 'user_edit', params: {id: row.id}}"><i class="fa fa-edit el-link el-link--primary mr-2"></i></router-link>
            <a class="cursor-pointer" @click="remove(row.id, row.name)"><i class="fa fa-trash-o el-link el-link--danger"></i></a>
          </div>
        </v-server-table>
      </el-card>
    </el-col>
  </el-row>
</template>
<script>
import UserResource from '@/api/user';
const userResource = new UserResource();
export default {
  data() {
    return {
      table: {
        columns: ['id', 'name', 'email', 'roles', 'created_at', 'actions'],
        options: {
          requestFunction: function(query) {
            return userResource.list(query);
          },
          headings: {
            id: () => this.$t('table.user.id'),
            name: () => this.$t('table.user.name'),
            'role.name': () => this.$t('table.user.role'),
            created_at: () => this.$t('date.created_at'),
          },
          columnsClasses: {
            id: 'text-center',
            created_at: 'text-center',
            roles: 'text-center',
          },
          templates: {
            created_at: (h, row) => {
              return this.$options.filters.formatDate(row.created_at);
            },
            roles: (h, row) => {
              return row.roles.map((value) => {
                return value.name;
              });
            },
          },
          sortable: ['id', 'created_at'],
        },
      },
      loading: false,
    };
  },
  mounted() {
    Event.$on('vue-tables.loading', () => {
      this.loading = true;
    });
    Event.$on('vue-tables.loaded', () => {
      this.loading = false;
    });
  },
  methods: {
    remove(id, name) {
      this.$confirm(this.$t('messages.delete_confirm', { attribute: name }), this.$t('messages.warning'), {
        confirmButtonClass: 'outline-none',
        confirmButtonText: this.$t('button.ok'),
        cancelButtonClass: this.$t('button.cancel'),
        type: 'warning',
        center: true,
      }).then(() => {
        userResource.destroy(id).then(() => {
          const index = this.$refs.table_user.data.findIndex((value) => value.id === id);
          this.$refs.table_user.data.splice(index, 1);
          this.$message({
            showClose: true,
            message: this.$t('messages.delete'),
            type: 'success',
          });
        });
      });
    },
  },
};
</script>
