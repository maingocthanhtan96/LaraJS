<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header" class="flex justify-between items-center">
          <div></div>
          <button @click="dialogVisible = true" class="hover:bg-green-600 hover:text-white font-bold border rounded border-green-600 text-green-600 bg-transparent py-3 px-4"><svg-icon icon-class="tree" /></button>
          <router-link :to="{name: 'generator_create'}" class="hover:bg-indigo-600 hover:text-white font-bold border rounded border-indigo-600 text-indigo-600 bg-transparent py-3 px-4" tag="button">
            <i class="fa fa-plus mr-2" />Create
          </router-link>
        </div>
        <v-server-table
          class="generate-table"
          ref="table_generator"
          name="table_generator"
          :columns="table.columns"
          :options="table.options"
        >
          <template v-if="loading" slot="afterBody">
            <div v-loading="loading" class="overlay-loader" />
          </template>
          <template slot="id" slot-scope="props">{{ props.index }}</template>
          <div slot="actions" slot-scope="{row}" class="flex justify-center items-center">
            <router-link :to="{name: 'generator_edit', params: {id: row.id}}">
              <el-tooltip effect="dark" content="Edit" placement="left">
                <i class="fa fa-edit has-text-info mr-4" />
              </el-tooltip>
            </router-link>
            <router-link :to="{name: 'generator_relationship', params: {id: row.id}}">
              <el-tooltip effect="dark" content="Relationship" placement="right">
                <svg-icon class="has-text-success" icon-class="tree" />
              </el-tooltip>
            </router-link>
          </div>
        </v-server-table>
      </el-card>
    </el-col>
    <div class="container is-fullhd">
      <el-dialog
        :visible.sync="dialogVisible"
        :fullscreen="true"
      >
        <div slot="title" class="text-center">
          <h3 class="title">Diagram {{$t('route.generator_relationship')}}</h3>
        </div>
        <div>
          <div class="tree text-center"><ul style="display: inline-block;"><li><a href="#">Banner</a> <ul><li><a href="#">hasMany</a> <ul><li><a href="#">Page</a> <ul><li><a href="#">page_id</a></li> <li><a href="#">id</a></li></ul></li></ul></li> <li><a href="#">hasMany</a> <ul><li><a href="#">JobFair</a> <ul><li><a href="#">job_fair_id</a></li> <li><a href="#">id</a></li></ul></li></ul></li></ul></li></ul></div>
        </div>
      </el-dialog>
    </div>
  </el-row>
</template>
<script>
import GeneratorResource from '@/api/generator';

const generatorResource = new GeneratorResource();

export default {
  data() {
    return {
      dialogVisible: false,
      table: {
        columns: ['id', 'table', 'created_at', 'actions'],
        options: {
          requestFunction: function(query) {
            return generatorResource.list(query);
          },
          headings: {
            id: () => this.$t('table.user.id'),
            table: () => this.$t('table.user.name'),
            created_at: () => this.$t('date.created_at'),
          },
          columnsClasses: {
            id: 'has-text-centered w-24',
            created_at: 'has-text-centered',
          },
          templates: {
            created_at: (h, row) => {
              return this.$options.filters.formatDate(row.created_at);
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
        generatorResource.destroy(id).then(() => {
          const index = this.$refs.table_generator.data.findIndex((value) => value.id === id);
          this.$refs.table_generator.data.splice(index, 1);
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
<style lang="scss" scoped>
 .generate-table {
   &::v-deep thead > tr > th{
     &:nth-child(4) {
       width: 245px;
     }
   }
 }
</style>
