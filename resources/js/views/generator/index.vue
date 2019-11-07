<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header" class="flex justify-between items-center">
          <div></div>
          <button @click="dialogVisible = true"
                  class="hover:bg-green-600 hover:text-white font-bold border rounded border-green-600 text-green-600 bg-transparent py-3 px-4">
            <svg-icon icon-class="tree-table"/>
          </button>
          <router-link :to="{name: 'generator_create'}" class="pan-btn blue-btn" tag="button">
            <i class="fa fa-plus mr-2"/>Create
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
            <div v-loading="loading" class="overlay-loader"/>
          </template>
          <template slot="id" slot-scope="props">{{ props.index }}</template>
          <div slot="actions" slot-scope="{row}" class="flex justify-center items-center">
            <router-link :to="{name: 'generator_edit', params: {id: row.id}}">
              <el-tooltip effect="dark" content="Edit" placement="left">
                <i class="fa fa-edit el-link el-link--primary mr-4"></i>
              </el-tooltip>
            </router-link>
            <router-link :to="{name: 'generator_relationship', params: {id: row.id}}">
              <el-tooltip effect="dark" content="Relationship" placement="right">
                <svg-icon class="el-link el-link--success" icon-class="tree"/>
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
          <div class="demo-image__preview">
            <el-image
              :src="this.diagram"
              :preview-src-list="[this.diagram]">
            </el-image>
          </div>
          <svg-icon icon-class="diagram-erd"/>
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
      diagram: require('@/assets/images/diagram-erd.png'),
      dialogVisible: false,
      table: {
        columns: ['id', 'table', 'created_at', 'actions'],
        options: {
          requestFunction: function (query) {
            return generatorResource.list(query);
          },
          headings: {
            id: () => this.$t('table.user.id'),
            table: () => this.$t('table.user.name'),
            created_at: () => this.$t('date.created_at'),
          },
          columnsClasses: {
            id: 'text-center w-24',
            created_at: 'text-center',
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
    &::v-deep thead > tr > th {
      &:nth-child(4) {
        width: 245px;
      }
    }
  }
</style>