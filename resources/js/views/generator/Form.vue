<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header">
          <h1 class="text-4xl">
            <mallki class-name="mallki-text" :text="$t('route.generator')" />
          </h1>
        </div>
        <section class="content-wrapper">
          <div class="container is-fullhd">
            <div>
              <el-form
                ref="formModel"
                :model="formModel"
                :rules="modalRules"
                label-position="top"
                status-icon
              >
                <el-col :span="9" class="mr-4">
                  <el-form-item
                    :label="$t('generator.form_model_name')"
                    prop="name"
                  >
                    <el-input
                      v-model="formModel.name"
                      :placeholder="
                        $t('generator.form_model_name') + ' (Ex: CategoryPost)'
                      "
                      :disabled="disabledModel()"
                    />
                  </el-form-item>
                </el-col>
                <el-col :span="9" class="mr-4">
                  <el-form-item
                    :label="$t('generator.form_model_name_trans')"
                    prop="name_trans"
                  >
                    <el-input
                      v-model="formModel.name_trans"
                      :placeholder="$t('generator.form_model_name_trans')"
                      :disabled="disabledModel()"
                    />
                  </el-form-item>
                </el-col>
                <el-col :span="2">
                  <el-form-item :label="$t('generator.limit')">
                    <el-select
                      v-model="formModel.limit"
                      :placeholder="$t('generator.limit')"
                      disabled
                    >
                      <el-option
                        v-for="(limit, index) in limits"
                        :key="'limit_' + index"
                        :label="limit"
                        :value="limit"
                      />
                    </el-select>
                  </el-form-item>
                </el-col>
                <el-col :span="24">
                  <el-form-item :label="$t('generator.options')">
                    <el-checkbox-group v-model="formModel.options">
                      <el-checkbox
                        label="Soft Deletes"
                        checked
                        :disabled="$route.params.id > 0"
                      />
                      <el-checkbox label="Datatables" disabled />
                      <el-checkbox
                        label="Role Admin"
                        :disabled="$route.params.id > 0"
                      />
                      <el-tooltip
                        class="item"
                        effect="light"
                        content="Not run artisan migrate"
                        placement="top"
                      >
                        <el-checkbox
                          label="Ignore Migrate"
                          :disabled="$route.params.id > 0"
                        />
                      </el-tooltip>
                      <el-checkbox label="Test Cases" disabled />
                    </el-checkbox-group>
                  </el-form-item>
                </el-col>
              </el-form>
            </div>
            <div class="divTable">
              <div class="divTableBody">
                <div class="divTableRow">
                  <div class="divTableCell text-center">
                    <b>{{ $t('generator.no') }}</b>
                  </div>
                  <div class="divTableCell text-center">
                    <b>{{ $t('generator.field_name') }}</b>
                  </div>
                  <div class="divTableCell text-center">
                    <b>{{ $t('generator.field_name_trans') }}</b>
                  </div>
                  <div class="divTableCell text-center">
                    <b>{{ $t('generator.db_type') }}</b>
                  </div>
                  <div class="divTableCell text-center">
                    <b>{{ $t('generator.default_value') }}</b>
                  </div>
                  <div
                    v-if="$route.params.id > 0"
                    class="divTableCell text-center"
                  >
                    <b>{{ $t('generator.after_column') }}</b>
                  </div>
                  <div
                    class="divTableCell text-center"
                    style="max-width: 70px;width: 70px"
                  >
                    <b>{{ $t('generator.search') }}</b>
                  </div>
                  <div
                    class="divTableCell text-center"
                    style="max-width: 70px;width: 70px"
                  >
                    <b>{{ $t('generator.sort') }}</b>
                  </div>
                  <div
                    class="divTableCell text-center"
                    style="max-width: 70px;width: 70px"
                  >
                    <b>{{ $t('generator.show') }}</b>
                  </div>
                  <div
                    class="divTableCell text-center"
                    style="max-width: 70px;width: 70px"
                  >
                    <b>{{ $t('generator.delete') }}</b>
                  </div>
                </div>
                <draggable
                  v-model="form"
                  style="display: contents"
                  :options="{ draggable: '.draggable', animation: 400 }"
                >
                  <el-form
                    v-for="(data, index) in form"
                    :key="'form_' + index"
                    :ref="`dynamicFieldsForm${index}`"
                    :model="data"
                    :rules="dynamicFieldsRules"
                    class="divTableRow hover:bg-gray-200"
                    :class="{
                      draggable:
                        !disabledMethod(index) && index >= formTemp.length,
                    }"
                    status-icon
                  >
                    <div class="divTableCell text-center align-middle">
                      {{ index + 1 }}
                    </div>
                    <div class="divTableCell text-center align-middle pt-8">
                      <el-form-item prop="field_name">
                        <el-input
                          v-model="data.field_name"
                          placeholder="Field Name"
                          :disabled="disabledMethod(index)"
                        />
                      </el-form-item>
                    </div>
                    <div class="divTableCell text-center align-middle pt-8">
                      <el-form-item prop="field_name_trans">
                        <el-input
                          v-model="data.field_name_trans"
                          placeholder="Field Name Trans"
                          :disabled="
                            disabledMethod(index) || disableTrans(data.id)
                          "
                        />
                      </el-form-item>
                    </div>
                    <div class="divTableCell text-center align-middle pt-8">
                      <el-form-item prop="db_type">
                        <el-select
                          v-model="data.db_type"
                          filterable
                          placeholder="DB Type"
                          :disabled="disabledMethod(index)"
                          @change="changeDBType(index, data.db_type)"
                        >
                          <el-option
                            v-for="(type, i) in dbTypeDefault"
                            :key="'dbTypeDefault_' + i"
                            :label="type"
                            :value="type"
                          />
                          <el-option-group
                            v-for="(group, i) in dbType"
                            :key="'group_' + i"
                            :label="group.label"
                          >
                            <el-option
                              v-for="(item, n) in group.options"
                              :key="'option_' + n"
                              :label="item"
                              :value="item"
                            />
                          </el-option-group>
                        </el-select>
                      </el-form-item>
                      <template v-if="data.db_type === 'ENUM'">
                        <el-form-item prop="enum">
                          <el-select
                            v-model="data.enum"
                            multiple
                            filterable
                            allow-create
                            default-first-option
                            placeholder="Enum"
                          />
                        </el-form-item>
                      </template>
                      <template v-if="data.db_type === 'VARCHAR'">
                        <el-form-item prop="length_varchar">
                          <el-input-number
                            v-model="data.length_varchar"
                            :min="1"
                            :max="191"
                          />
                        </el-form-item>
                      </template>
                    </div>
                    <div class="divTableCell text-center align-middle pt-8">
                      <el-form-item prop="default_value">
                        <el-select
                          v-if="notAs.includes(data.db_type)"
                          v-model="data.default_value"
                          placeholder="Default Value"
                          :disabled="disabledMethod(index)"
                        >
                          <el-option
                            v-for="(item, i) in defaultValueNotAs"
                            :key="'defaultValue_' + i"
                            :label="item"
                            :value="item"
                          />
                        </el-select>
                        <el-select
                          v-else
                          v-model="data.default_value"
                          placeholder="Default Value"
                          :disabled="disabledMethod(index)"
                        >
                          <el-option
                            v-for="(item, i) in defaultValue"
                            :key="'defaultValue_' + i"
                            :label="item"
                            :value="item"
                          />
                        </el-select>
                      </el-form-item>
                      <el-form-item
                        v-if="data.default_value === 'As define'"
                        prop="as_define"
                      >
                        <template v-if="showInputDefault(data.db_type)">
                          <el-select
                            v-if="data.db_type === 'ENUM'"
                            v-model="data.as_define"
                            placeholder="Default Value"
                          >
                            <el-option
                              v-for="(item, i) in data.enum"
                              :key="'enum_' + i"
                              :label="item"
                              :value="item"
                            />
                          </el-select>
                          <el-tooltip
                            v-if="data.db_type === 'BOOLEAN'"
                            :content="data.as_define === 0 ? 'false' : 'true'"
                            placement="top"
                          >
                            <el-switch
                              v-model="data.as_define"
                              :active-value="1"
                              :inactive-value="0"
                            />
                          </el-tooltip>
                          <el-date-picker
                            v-if="data.db_type === 'DATE'"
                            v-model="data.as_define"
                            type="date"
                            placeholder="Date"
                            value-format="yyyy-MM-dd"
                          />
                          <el-date-picker
                            v-if="data.db_type === 'DATETIME'"
                            v-model="data.as_define"
                            type="datetime"
                            placeholder="Date time"
                            value-format="yyyy-MM-dd HH:mm:ss"
                          />
                          <el-time-picker
                            v-if="data.db_type === 'TIME'"
                            v-model="data.as_define"
                            placeholder="Time"
                            value-format="HH:mm:ss"
                          />
                          <el-date-picker
                            v-if="data.db_type === 'YEAR'"
                            v-model="data.as_define"
                            type="year"
                            placeholder="YEAR"
                            value-format="yyyy"
                          />
                        </template>
                        <el-input
                          v-else
                          v-model="data.as_define"
                          placeholder="Default"
                          class="max-w-sm pt-5"
                        />
                      </el-form-item>
                    </div>
                    <div
                      v-if="$route.params.id > 0"
                      class="divTableCell text-center align-middle"
                    >
                      <el-select
                        v-if="disableAfterColumn(data.id)"
                        v-model="data.after_column"
                        filterable
                        placeholder="Select"
                      >
                        <el-option
                          v-for="(item, i) in afterColumn"
                          :key="'after_column_' + i"
                          :label="item.val"
                          :value="item.val"
                        />
                      </el-select>
                    </div>
                    <div class="divTableCell text-center align-middle">
                      <el-checkbox
                        v-model="data.search"
                        :disabled="
                          disabledMethod(index) ||
                            !data.show ||
                            notSearch.includes(data.db_type)
                        "
                      />
                    </div>
                    <div class="divTableCell text-center align-middle">
                      <el-checkbox
                        v-model="data.sort"
                        :disabled="!data.show || notSoft.includes(data.db_type)"
                      />
                    </div>
                    <div class="divTableCell text-center align-middle">
                      <el-checkbox
                        v-model="data.show"
                        @click.native="changeShow(index)"
                      />
                    </div>
                    <div class="divTableCell text-center align-middle">
                      <el-button
                        v-if="index > 0"
                        type="danger"
                        icon="el-icon-delete"
                        circle
                        @click.prevent="removeRow(index)"
                      />
                    </div>
                  </el-form>
                </draggable>
              </div>
            </div>
            <div class="pt-6">
              <el-button v-waves type="success" round @click.prevent="addField">
                {{ $t('generator.add_field') }}
              </el-button>
            </div>
            <div class="float-right">
              <el-button
                v-if="$route.params.id"
                v-waves
                v-loading.fullscreen.lock="loading"
                type="primary"
                round
                @click.prevent="
                  generateUpdate(`dynamicFieldsForm`, 'formModel')
                "
              >
                {{ $t('generator.generate_update') }}
              </el-button>
              <el-button
                v-else
                v-waves
                v-loading.fullscreen.lock="loading"
                type="primary"
                round
                @click.prevent="generate(`dynamicFieldsForm`, 'formModel')"
              >
                {{ $t('generator.generate') }}
              </el-button>
            </div>
          </div>
        </section>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import GeneratorResource from '@/api/generator';
import _ from 'lodash';
import draggable from 'vuedraggable';
import Mallki from '@/components/TextHoverEffect/Mallki';
import waves from '@/directive/waves/index.js'; // v-wave directive

const generatorResource = new GeneratorResource();

export default {
  components: {
    draggable,
    Mallki,
  },
  directives: {
    waves,
  },
  data() {
    return {
      form: [
        {
          id: 1,
          field_name: 'id',
          field_name_trans: 'No.',
          db_type: 'Increments',
          enum: [],
          length_varchar: 191,
          default_value: 'None',
          as_define: '',
          after_column: '',
          search: false,
          sort: true,
          show: true,
        },
      ],
      formModel: {
        name: '',
        name_trans: '',
        limit: 25,
        options: [],
      },
      formRename: [],
      formDrop: [],
      formChange: [],
      formTemp: [],
      limits: [10, 25, 50, 100, 200],
      dbTypeDefault: ['INT', 'VARCHAR', 'TEXT', 'DATE'],
      dbType: [
        {
          label: 'Numeric',
          options: ['INT', 'BIGINT', 'FLOAT', 'DOUBLE', 'BOOLEAN'],
        },
        {
          label: 'Date and time',
          options: ['DATE', 'DATETIME', 'TIME', 'YEAR'],
        },
        {
          label: 'String',
          options: ['VARCHAR', 'TEXT', 'LONGTEXT', 'ENUM'],
        },
        {
          label: 'JSON',
          options: ['JSON'],
        },
        {
          label: 'FILE',
          options: ['FILE'],
        },
      ],
      defaultValue: ['None', 'NULL', 'As define'],
      defaultValueNotAs: ['None', 'NULL'],
      notAs: ['TEXT', 'LONGTEXT', 'FILE'],
      afterColumn: [],
      notSearch: ['FILE', 'JSON'],
      notSoft: ['FILE', 'JSON'],
      loading: false,
      redirectLocation: '/be/administrators/generator',
    };
  },
  computed: {
    dynamicFieldsRules() {
      return {
        field_name: [
          {
            validator: (rule, value, callback) => {
              if (value === '') {
                callback(
                  new Error(
                    this.$t('validation.required', {
                      attribute: this.$t('generator.field_name'),
                    })
                  )
                );
              } else {
                const arrayIndex = [];
                for (const val of this.form) {
                  if (val.field_name.toLowerCase() === value.toLowerCase()) {
                    arrayIndex.push(val.id);
                    if (arrayIndex.length > 1) {
                      callback(new Error(this.$t('generator.column_exist')));
                      break;
                    }
                  }
                }
                if (arrayIndex.length <= 1) {
                  callback();
                }
              }
            },
            trigger: ['change', 'blur'],
          },
        ],
        field_name_trans: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('generator.field_name_trans'),
            }),
            trigger: ['change', 'blur'],
          },
        ],
        db_type: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('generator.db_type'),
            }),
            trigger: ['change', 'blur'],
          },
        ],
        enum: [
          {
            required: true,
            message: this.$t('validation.required', { attribute: 'Enum' }),
            trigger: ['change', 'blur'],
          },
        ],
        default_value: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('generator.default_value'),
            }),
            trigger: ['change', 'blur'],
          },
        ],
        as_define: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('generator.as_define'),
            }),
            trigger: ['change', 'blur'],
          },
        ],
      };
    },
    modalRules() {
      return {
        name: [
          {
            validator: (rule, value, callback) => {
              if (value === '') {
                callback(
                  new Error(
                    this.$t('validation.required', {
                      attribute: this.$t('generator.form_model_name'),
                    })
                  )
                );
              } else {
                this.checkModelMethod(callback);
              }
            },
            trigger: ['change'],
          },
        ],
        name_trans: [
          {
            required: true,
            message: this.$t('validation.required', {
              attribute: this.$t('generator.form_model_name_trans'),
            }),
            trigger: ['change', 'blur'],
          },
        ],
      };
    },
  },
  watch: {
    form: {
      handler(val) {
        // Return the object that changed
        this.formRename = [];
        this.formChange = [];
        const changedFieldName = val.filter((p, idx) => {
          if (this.formTemp[idx]) {
            return p.field_name !== this.formTemp[idx].field_name;
          }
        });
        const changeFields = val.filter((p, idx) => {
          if (this.formTemp[idx]) {
            return !_.isEqual(p, this.formTemp[idx]);
          }
        });
        this.formChange = changeFields;
        changedFieldName.forEach(val => {
          const index = this.formTemp.findIndex(temp => val.id === temp.id);
          this.formRename.push({
            field_name_new: val,
            field_name_old: this.formTemp[index],
          });
        });
        this.form.forEach((column, indexColumn) => {
          this.afterColumn.forEach((field, indexField) => {
            if (isNaN(field.id)) {
              if (this.formTemp[indexColumn]) {
                if (
                  `temp-${this.formTemp[indexColumn].field_name}` === field.id
                ) {
                  this.afterColumn[indexField].val = column.field_name;
                }
              }
              return;
            }
            if (column.id === field.id) {
              this.afterColumn[indexField].val = column.field_name;
            }
          });
        });
      },
      deep: true,
    },
  },
  async mounted() {
    const { id } = this.$route.params;
    if (id) {
      await generatorResource.get(id).then(res => {
        const { data } = res.data;
        this.form = JSON.parse(data.field);
        this.formTemp = JSON.parse(data.field);
        this.formModel = JSON.parse(data.model);
      });
      await generatorResource.getColumns(this.formModel.name).then(res => {
        const { data } = res.data;
        this.afterColumn = data.map(val => {
          return { id: `temp-${val}`, val };
        });
      });
    }
  },
  methods: {
    changeDBType(index, dbType) {
      // not As define
      if (this.notAs.includes(dbType)) {
        this.form[index].default_value = 'NULL';
        this.form[index].as_define = '';
      }
      // not checkbox search
      if (this.notSearch.includes(dbType)) {
        this.form[index].search = false;
      }
      // not checkbox soft
      if (this.notSoft.includes(dbType)) {
        this.form[index].sort = false;
      }
    },
    showInputDefault(dbType) {
      const arDbType = ['YEAR', 'TIME', 'DATETIME', 'DATE', 'BOOLEAN', 'ENUM'];
      return arDbType.indexOf(dbType) !== -1;
    },
    checkModelMethod: _.debounce(function(cb) {
      generatorResource.checkModel(this.formModel.name).then(res => {
        const { message } = res.data;
        if (message === 1) {
          cb(new Error(this.$t('generator.table_exist')));
        } else if (message === 3) {
          cb(
            new Error(
              this.$t('validation.required', {
                attribute: this.$t('generator.form_model_name'),
              })
            )
          );
        } else if (message === 2) {
          cb();
        }
      });
    }, 500),
    checkColumnMethod: _.debounce(function(cb, column) {
      generatorResource.checkColumn(this.formModel.name, column).then(res => {
        const { message } = res.data;
        if (message === 1) {
          cb(new Error(this.$t('generator.column_exist')));
        } else if (message === 3) {
          cb(
            new Error(
              this.$t('validation.required', {
                attribute: this.$t('generator.field_name'),
              })
            )
          );
        } else if (message === 2) {
          cb();
        }
      });
    }, 500),
    checkValidateFormModel(model) {
      return new Promise((resolve, reject) => {
        this.$refs[model].validate(valid => {
          if (!valid) {
            reject();
          } else {
            resolve();
          }
        });
      });
    },
    async generate(name, model) {
      this.loading = true;
      let flag = true;
      this.form.forEach((value, index) => {
        this.$refs[name + index][0].validate(valid => {
          if (!valid) {
            flag = false;
          }
        });
      });
      await this.checkValidateFormModel(model).catch(() => {
        flag = false;
      });

      if (flag) {
        generatorResource
          .store({
            model: this.formModel,
            fields: this.form,
          })
          .then(() => {
            this.$message({
              showClose: true,
              message: this.$t('messages.create'),
              type: 'success',
            });
            this.loading = false;
            window.location.href = this.redirectLocation;
          })
          .catch(() => {
            this.loading = false;
            // window.location.href = this.redirectLocation;
          });
      } else {
        this.loading = false;
      }
    },
    async generateUpdate(name) {
      this.loading = true;
      let flag = true;
      this.form.forEach((value, index) => {
        this.$refs[name + index][0].validate(valid => {
          if (!valid) {
            flag = false;
          }
        });
      });

      if (flag) {
        // remove form => fields exist
        const formClone = _.cloneDeep(this.form);
        formClone.splice(0, this.formTemp.length);
        generatorResource
          .update(this.$route.params.id, {
            model: this.formModel,
            fields: this.form,
            fields_update: formClone,
            rename: this.formRename,
            change: this.formChange,
            drop: this.formDrop,
          })
          .then(() => {
            this.loading = false;
            this.$message({
              showClose: true,
              message: this.$t('messages.update'),
              type: 'success',
            });
            window.location.href = this.redirectLocation;
          })
          .catch(() => {
            this.loading = false;
            // window.location.href = this.redirectLocation;
          });
      } else {
        this.loading = false;
      }
    },
    disabledMethod(index) {
      return index === 0;
    },
    disabledModel() {
      return typeof this.$route.params.id !== 'undefined';
    },
    addField() {
      let newID = 1;
      for (let i = 0; i < this.form.length; i++) {
        newID = this.form[i].id + 1;
      }
      this.form.push({
        id: newID,
        field_name: '',
        field_name_trans: '',
        db_type: 'VARCHAR',
        enum: [],
        length_varchar: 191,
        default_value: 'NULL',
        as_define: '',
        after_column: '',
        search: true,
        sort: true,
        show: true,
      });
      this.afterColumn.push({ id: newID, val: '' });
    },
    removeRow(index) {
      this.$confirm(this.$t('generator.confirm_remove_row'), 'Warning', {
        confirmButtonText: this.$t('button.ok'),
        cancelButtonText: this.$t('button.cancel'),
        type: 'warning',
      })
        .then(() => {
          if (this.$route.params.id && this.formTemp[index]) {
            this.formDrop.push(this.formTemp[index]);
            this.formTemp.splice(index, 1);
          }
          const formCurrent = this.form[index];
          const afterIndex = this.afterColumn.findIndex(
            val => val.val === formCurrent.field_name
          );
          if (afterIndex !== -1) {
            this.afterColumn.splice(afterIndex, 1);
          }
          this.form.splice(index, 1);
          this.$message({
            type: 'success',
            message: this.$t('messages.success'),
          });
        })
        .catch(() => {});
    },
    changeShow(index) {
      if (!this.form[index].show) {
        this.form[index].sort = true;
        this.form[index].search = true;
      } else {
        this.form[index].sort = false;
        this.form[index].search = false;
      }
    },
    disableAfterColumn(id) {
      const formClone = _.cloneDeep(this.form);
      formClone.splice(0, this.formTemp.length);
      return formClone.some(val => val.id === id);
    },
    disableTrans(id) {
      return this.formTemp.some(val => val.id === id);
    },
  },
};
</script>

<style lang="scss" scoped>
/* DivTable.com */
.divTable {
  display: table;
  width: 100%;
}

.divTableRow {
  display: table-row;
}

.divTableHeading {
  background-color: #eee;
  display: table-header-group;
}

.divTableCell,
.divTableHead {
  border: 1px solid #999999;
  display: table-cell;
  padding: 3px 10px;
}

.divTableHeading {
  background-color: #eee;
  display: table-header-group;
  font-weight: bold;
}

.divTableFoot {
  background-color: #eee;
  display: table-footer-group;
  font-weight: bold;
}

.divTableBody {
  display: table-row-group;
}
</style>
<style lang="scss">
.el-message-box {
  width: 98% !important;
  max-width: 420px;
}
</style>
