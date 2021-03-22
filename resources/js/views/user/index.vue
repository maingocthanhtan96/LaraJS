<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header" class="tw-flex tw-justify-end tw-items-center">
          <router-link
            v-permission="['create']"
            :to="{ name: 'UserCreate' }"
            class="pan-btn blue-btn"
            tag="button"
          >
            <i class="el-icon-plus mr-2" />
            {{ $t('button.create') }}
          </router-link>
        </div>
        <div class="tw-flex tw-flex-col">
          <el-col :span="24" class="tw-mb-6">
            <el-col :xs="24" :sm="10" :md="6">
              <label>{{ $t('table.texts.filter') }}</label>
              <el-input
                v-model="table.listQuery.search"
                :placeholder="$t('table.texts.filterPlaceholder')"
              />
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
              <el-table-column
                align="center"
                sortable="custom"
                prop="id"
                :label="$t('table.user.id')"
                width="70px"
              >
                <template slot-scope="{ $index }">
                  {{ numericalOrder($index) }}
                </template>
              </el-table-column>
              <el-table-column
                data-generator="name"
                align="left"
                header-align="center"
                :label="$t('table.user.name')"
              >
                <template slot-scope="{ row }">
                  {{ row.name }}
                </template>
              </el-table-column>
              <el-table-column
                data-generator="email"
                align="left"
                header-align="center"
                :label="$t('table.user.email')"
              >
                <template slot-scope="{ row }">
                  {{ row.email }}
                </template>
              </el-table-column>
              <el-table-column align="center" :label="$t('table.user.avatar')">
                <template slot-scope="{ row }">
                  <el-avatar :size="60" :src="row.avatar">
                    <img :src="avatarFail" />
                  </el-avatar>
                </template>
              </el-table-column>
              <el-table-column align="center" :label="$t('table.user.role')">
                <template slot-scope="{ row }">
                  {{ row.roles[0].name }}
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
              <el-table-column :label="$t('table.actions')" align="center">
                <template slot-scope="{ row }">
                  <router-link
                    v-permission="['edit']"
                    :to="{ name: 'UserEdit', params: { id: row.id } }"
                  >
                    <i class="el-icon-edit el-link el-link--primary mr-2" />
                  </router-link>
                  <a
                    v-permission="['delete']"
                    class="cursor-pointer"
                    @click.stop="() => remove(row.id)"
                  >
                    <i class="el-icon-delete el-link el-link--danger" />
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
  </el-row>
</template>
<script>
import DateRangePicker from '@/plugins/mixins/date-range-picker';
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import { debounce } from '@/utils';
import UserResource from '@/api/user';

const userResource = new UserResource();

export default {
  components: { Pagination },
  mixins: [DateRangePicker],
  data() {
    return {
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
      avatarFail: require('@/assets/images/avatar-default.png').default,
    };
  },
  watch: {
    'table.listQuery.search': debounce(function () {
      this.handleFilter();
    }, 500),
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.table.loading = true;
      const { data } = await userResource.list(this.table.listQuery);
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
        const startDate = this.parseTimeToTz(date[0]);
        const endDate = this.parseTimeToTz(date[1]);
        this.table.listQuery.updated_at = [startDate, endDate];
      } else {
        this.table.listQuery.updated_at = [];
      }
      this.handleFilter();
    },
    sortChange(data) {
      const { prop, order } = data;
      this.table.listQuery.orderBy = prop;
      if (order === 'ascending') {
        this.table.listQuery.ascending = 0;
      } else {
        this.table.listQuery.ascending = 1;
      }
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
        await userResource.destroy(id);
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
    numericalOrder(index) {
      const table = this.table.listQuery;
      return (table.page - 1) * table.limit + index + 1;
    },
  },
};
</script>
