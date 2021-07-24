<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header" class="tw-flex tw-justify-between tw-items-center">
          <div />
          <button
            class="
              hover:tw-bg-green-600 hover:tw-text-white
              tw-font-bold tw-border tw-rounded tw-border-green-600 tw-text-green-600 tw-bg-transparent tw-py-3 tw-px-4
            "
            @click="dialogVisible = true"
          >
            <svg-icon icon-class="tree-table" />
          </button>
          <router-link :to="{ name: 'GeneratorCreate' }" class="pan-btn blue-btn" tag="button">
            <i class="el-icon-plus tw-mr-2" />
            Create
          </router-link>
        </div>
        <div class="tw-flex tw-flex-col">
          <el-col :span="24" class="tw-mb-6">
            <el-col :xs="24" :sm="10" :md="6">
              <label>{{ $t('table.texts.filter') }}</label>
              <el-input v-model="table.listQuery.search" :placeholder="$t('table.texts.filterPlaceholder')" />
            </el-col>
            <el-col :xs="24" :sm="14" :md="18">
              <br />
              <el-date-picker
                v-model="table.listQuery.updated_at"
                class="md:tw-float-right"
                type="daterange"
                :start-placeholder="$t('date.start_date')"
                :end-placeholder="$t('date.end_date')"
                :picker-options="pickerOptions"
                @change="changeDateRangePicker"
              />
            </el-col>
          </el-col>
          <el-col :span="24" class="table-responsive">
            <el-table
              v-loading="table.loading"
              class="w-full"
              :data="table.list"
              :default-sort="{ prop: 'updated_at', order: 'descending' }"
              border
              fit
              highlight-current-row
              @sort-change="sortChange"
            >
              <el-table-column align="center" sortable="custom" prop="id" label="No." width="70px">
                <template slot-scope="{ row }">
                  {{ row.id }}
                </template>
              </el-table-column>
              <el-table-column align="center" label="Table">
                <template slot-scope="{ row }">
                  {{ row.table }}
                </template>
              </el-table-column>
              <!--{{$TEMPLATES_NOT_DELETE_THIS_LINE$}}-->
              <el-table-column
                data-generator="updated_at"
                prop="updated_at"
                :label="$t('date.updated_at')"
                sortable="custom"
                align="center"
                header-align="center"
              >
                <template slot-scope="{ row }">
                  {{ row.updated_at | parseTime('{y}-{m}-{d}') }}
                </template>
              </el-table-column>
              <el-table-column :label="$t('table.actions')" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{ row }">
                  <router-link :to="{ name: 'GeneratorEdit', params: { id: row.id } }">
                    <el-tooltip effect="dark" content="Update" placement="left">
                      <i class="el-icon-edit el-link el-link--primary tw-mr-4" />
                    </el-tooltip>
                  </router-link>
                  <router-link
                    :to="{
                      name: 'GeneratorRelationship',
                      params: { id: row.id },
                    }"
                  >
                    <el-tooltip effect="dark" content="Relationship" placement="top">
                      <svg-icon class="el-link el-link--success tw-mr-4" icon-class="tree" />
                    </el-tooltip>
                  </router-link>
                  <a
                    v-if="row.id !== 1"
                    v-permission="['delete']"
                    class="cursor-pointer"
                    @click.stop="() => remove(row.id)"
                  >
                    <el-tooltip effect="dark" content="Remove" placement="right">
                      <i class="el-icon-delete el-link el-link--danger" />
                    </el-tooltip>
                  </a>
                </template>
              </el-table-column>
            </el-table>
            <pagination
              v-if="table.total > 0"
              :total="table.total"
              :page.sync="table.listQuery.page"
              :limit.sync="table.listQuery.limit"
              @pagination="getList"
            />
          </el-col>
        </div>
      </el-card>
    </el-col>
    <div class="container tw-is-fullhd">
      <el-dialog :visible.sync="dialogVisible" :fullscreen="true">
        <div slot="title" class="text-center">
          <h3 class="title">Diagram {{ $t('route.generator_relationship') }}</h3>
        </div>
        <div>
          <div class="demo-image__preview">
            <el-image :src="diagram" :preview-src-list="[diagram]" />
          </div>
          <svg-icon icon-class="diagram-erd" />
        </div>
      </el-dialog>
    </div>
  </el-row>
</template>
<script>
import DateRangePicker from '@/plugins/mixins/date-range-picker';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import GeneratorResource from '@/api/generator';
import { debounce } from '@/utils';

const generatorResource = new GeneratorResource();

export default {
  components: { Pagination },
  mixins: [DateRangePicker],
  data() {
    return {
      diagram: require('@/assets/images/diagram-erd.png').default,
      dialogVisible: false,
      table: {
        listQuery: {
          search: '',
          limit: 25,
          ascending: 1,
          page: 1,
          orderBy: 'updated_at',
          updated_at: [],
        },
        list: null,
        total: 0,
        loading: false,
      },
    };
  },
  watch: {
    'table.listQuery.search': debounce(function () {
      this.handleFilter();
    }, 500),
  },
  mounted() {
    this.getList();
  },
  methods: {
    async getList() {
      this.table.loading = true;
      const { data } = await generatorResource.list(this.table.listQuery);
      this.table.list = data.data;
      this.table.total = data.count;

      // Just to simulate the time of the request
      this.table.loading = false;
    },
    handleFilter() {
      this.table.listQuery.page = 1;
      this.getList();
    },
    changeDateRangePicker(date) {
      if (date) {
        const startDate = this.parseTime(date[0]);
        const endDate = this.parseTime(date[1]);
        this.table.listQuery.updated_at = [startDate, endDate];
      } else {
        this.table.listQuery.updated_at = [];
      }
      this.handleFilter();
    },
    sortChange(data) {
      const { prop, order } = data;
      this.table.listQuery.orderBy = prop;
      this.table.listQuery.ascending = +(order === 'ascending');
      this.getList();
    },
    remove(id) {
      this.$confirm(
        this.$t('messages.delete_confirm', {
          attribute: this.$t('table.user.id') + '#' + id,
        }),
        this.$t('messages.warning'),
        {
          confirmButtonText: this.$t('button.ok'),
          cancelButtonClass: this.$t('button.cancel'),
          type: 'warning',
          center: true,
        }
      ).then(async () => {
        this.table.loading = true;
        await generatorResource.destroy(id);
        const index = this.table.list.findIndex(value => value.id === id);
        this.table.list.splice(index, 1);
        this.$message({
          showClose: true,
          message: this.$t('messages.delete'),
          type: 'success',
        });
        this.table.loading = false;
      });
    },
    parseTime(date, format = '{y}-{m}-{d}') {
      return this.$options.filters.parseTime(date, format);
    },
    numericalOrder(index) {
      const table = this.table.listQuery;
      return (table.page - 1) * table.limit + index + 1;
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
