<template>
  <el-row>
    <el-col :span="24">
      <el-card>
        <div slot="header" class="text-center">
          <button
            class="hover:bg-green-600 hover:text-white font-bold border rounded border-green-600 text-green-600 bg-transparent py-3 px-4"
            @click="dialogVisible = true"
          >
            <svg-icon icon-class="tree" />
          </button>
        </div>
        <section class="section">
          <div class="flex flex-col items-center">
            <el-tag type="success" effect="dark">
              {{ form.model_current }}
            </el-tag>
            <div class="w-04-rem h-24 bg-indigo-600 draw-arrow-down one" />
            <el-select
              v-model="form.relationship"
              :class="{ 'error-danger': errors.relationship }"
              placeholder="Relationship"
              @change="replaceTemplate('')"
            >
              <el-option
                v-for="(relationship, index) in relationshipOptions"
                :key="'relationship_' + index"
                :label="relationship"
                :value="relationship"
              />
            </el-select>
            <p v-if="errors.relationship" class="help is-danger text-lg">
              {{ errors.relationship[0] }}
            </p>
            <div class="w-04-rem h-24 bg-indigo-600 draw-arrow-down two" />
            <el-select
              v-model="form.model"
              :class="{ 'error-danger': errors.model }"
              :loading="loadingModel"
              filterable
              placeholder="Model"
              @change="replaceTemplate('display')"
            >
              <el-option
                v-for="(model, index) in modelOptions"
                :key="'model_' + index"
                :label="model"
                :value="model"
              />
            </el-select>
            <p v-if="errors.model" class="help is-danger text-lg">
              {{ errors.model[0] }}
            </p>
            <div class="w-04-rem h-24 bg-indigo-600 draw-arrow-down three" />
            <div class="z-10">
              <pre-code-tag :content="markdown" />
            </div>
            <div class="w-04-rem h-24 bg-indigo-600 draw-arrow-down four" />
            <el-select
              v-model="form.column"
              :class="{ 'error-danger': errors.column }"
              :loading="loadingDisplay"
              filterable
              placeholder="Display Column"
              class="z-10"
            >
              <el-option
                v-for="(col, index) in displayColumns"
                :key="'col_' + index"
                :label="col"
                :value="col"
              />
            </el-select>
            <template v-if="form.relationship === 'belongsToMany'">
              <div
                class="w-04-rem h-24 bg-indigo-600 draw-arrow-down equivalent"
              />
              <el-select
                v-model="form.column2"
                :class="{ 'error-danger': errors.column2 }"
                :loading="loadingDisplay"
                filterable
                placeholder="Display Column 2"
              >
                <el-option
                  v-for="(col, index) in displayColumns2"
                  :key="'col_' + index"
                  :label="col"
                  :value="col"
                />
              </el-select>
            </template>
            <p v-if="errors.column" class="help is-danger text-lg">
              {{ errors.column[0] }}
            </p>
            <div class="w-04-rem h-24 bg-indigo-600 draw-arrow-down five" />
            <el-select
              v-model="form.options"
              class="options"
              multiple
              placeholder="Options"
              @change="changeOptions(form.options)"
            >
              <el-option
                v-for="(item, key) in options"
                :key="'option_' + key"
                :label="item"
                :value="item"
              />
            </el-select>
            <div class="w-04-rem h-24 bg-indigo-600 draw-arrow-down six" />
            <el-tooltip
              effect="dark"
              :content="$t('route.generator_relationship')"
              placement="bottom"
            >
              <el-button
                v-loading.fullscreen.lock="loading"
                class="z-10"
                type="success"
                icon="el-icon-check"
                circle
                @click.prevent="createRelationship()"
              />
            </el-tooltip>
          </div>
          <!--dialog-->
          <div class="container is-fullhd">
            <el-dialog
              :visible.sync="dialogVisible"
              :fullscreen="true"
              @open="diagram"
            >
              <div slot="title" class="text-center">
                <h3 class="title">
                  Diagram {{ $t('route.generator_relationship') }}
                </h3>
              </div>
              <div>
                <div class="tree text-center">
                  <ul class="inline-block">
                    <li
                      v-for="(dg, index) in drawDiagram"
                      :key="'diagram_' + index"
                    >
                      <a>{{ diagram.model }}</a>
                      <ul class="flex">
                        <li
                          v-for="(item, i) in dg.data"
                          :key="'itemDiagram_' + i"
                        >
                          <a>{{ item.type }}</a>
                          <ul>
                            <li>
                              <a class="w-64">{{ item.model }}</a>
                              <ul
                                v-if="item.table"
                                :class="{ 'has-mtm-parent': item.table }"
                              >
                                <a class="w-64">{{ item.table }}</a>
                              </ul>
                              <ul>
                                <li>
                                  <a :class="{ 'has-mtm': item.table }">
                                    {{ item.foreign_key }}
                                  </a>
                                </li>
                                <li>
                                  <a :class="{ 'has-mtm': item.table }">
                                    {{ item.local_key }}
                                  </a>
                                </li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </div>
            </el-dialog>
          </div>
        </section>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
import PreCodeTag from '@/components/PreCodeTag';
import GeneratorResource from '@/api/generator';
import { camelCase, snakeCase } from 'lodash';

const generatorResource = new GeneratorResource();

export default {
  components: {
    PreCodeTag
  },
  data() {
    return {
      loading: false,
      loadingModel: false,
      loadingDisplay: false,
      dialogVisible: false,
      options: ['Search', 'Sort', 'Show'],
      relationshipOptions: ['hasOne', 'hasMany', 'belongsToMany'],
      modelOptions: [],
      markdown: '# Relationship',
      displayColumns: [],
      displayColumns2: [],
      form: {
        model_current: '',
        relationship: '',
        model: '',
        column: '',
        column2: '',
        options: ['Search', 'Sort', 'Show']
      },
      drawDiagram: []
    };
  },
  mounted() {
    const { id } = this.$route.params;
    if (id) {
      generatorResource.get(id).then(res => {
        const { model } = res.data.data;
        const modelCurrent = JSON.parse(model);
        this.form.model_current = modelCurrent.name;
        this.loadingModel = true;
        generatorResource
          .getModels(model)
          .then(res => {
            this.loadingModel = false;
            const { data } = res.data;
            this.modelOptions = data;
          })
          .catch(() => {
            this.loadingModel = false;
          });
      });
    }
  },
  methods: {
    diagram() {
      generatorResource.generateDiagram(this.form.model_current).then(res => {
        const { data } = res.data;
        this.drawDiagram = data;
      });
    },
    changeOptions(options) {
      if (!options.includes('Show')) {
        this.form.options = [];
        this.options = ['Show'];
      } else {
        if (this.form.relationship === 'belongsToMany') {
          this.options = ['Show', 'Search'];
        } else {
          this.options = ['Search', 'Sort', 'Show'];
        }
      }
    },
    createRelationship() {
      this.loading = true;
      generatorResource
        .generateRelationship(this.form)
        .then(res => {
          this.$message({
            showClose: true,
            message: this.$t('messages.success'),
            type: 'success'
          });
          this.loading = false;
          location.reload();
        })
        .catch(() => {
          this.loading = false;
          location.reload();
        });
    },
    replaceTemplate(model) {
      const template = `# Model ${this.form.model_current}
      public function ${camelCase(this.form.model)}() {
        return $this->${this.form.relationship}(${this.form.model}::class);
      }`;
      let templateInverse = `<br/>  # Model ${this.form.model}
      public function ${camelCase(this.form.model_current)}() {
        return $this->belongsTo(${this.form.model_current}::class);
      }`;
      if (this.form.relationship === 'belongsToMany') {
        templateInverse = `<br/>  # Model ${this.form.model}
      public function ${camelCase(this.form.model_current)}() {
        return $this->belongsToMany(${this.form.model_current}::class);
      }`;
        templateInverse += `<br/>  # I will create a table ${snakeCase(
          this.form.model_current
        )}_${snakeCase(this.form.model)}`;
      }
      if (this.form.relationship && this.form.model) {
        this.markdown = template.concat(templateInverse);
      }
      if (this.form.relationship === 'belongsToMany') {
        this.options = ['Show', 'Search'];
        this.form.options = ['Show', 'Search'];
      } else {
        this.options = ['Search', 'Sort', 'Show'];
        this.form.options = ['Search', 'Sort', 'Show'];
      }
      if (model) {
        this.loadingDisplay = true;
        generatorResource
          .getColumns(this.form.model_current)
          .then(res => {
            this.loadingDisplay = false;
            const { data } = res.data;
            this.displayColumns = data;
          })
          .catch(() => {
            this.loadingDisplay = false;
          });
        generatorResource
          .getColumns(this.form.model)
          .then(res => {
            this.loadingDisplay = false;
            const { data } = res.data;
            this.displayColumns2 = data;
          })
          .catch(() => {
            this.loadingDisplay = false;
          });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
$indigo600: #5a67d8;
@mixin circleNumber($number) {
  &:after {
    content: $number;
    background-color: $indigo600;
    position: absolute;
    top: 14px;
    left: -8px;
    color: #fff;
    width: 20px;
    height: 20px;
    border-radius: 100%;
    text-align: center;
    line-height: 20px;
  }
}

.w-04-rem {
  width: 0.4rem;
}

.draw-arrow-down {
  position: relative;

  &:before {
    content: '';
    position: absolute;
    bottom: -1rem;
    right: -0.3rem;
    width: 0;
    height: 0;
    border-left: 0.5rem solid transparent;
    border-right: 0.5rem solid transparent;
    border-top: 1.9rem solid $indigo600;
  }
}

.error-danger {
  &::v-deep input {
    border-color: #ff3860 !important;
  }
}

.options {
  width: 260px;
}

.one {
  @include circleNumber('1');
}

.two {
  @include circleNumber('2');
}

.three {
  @include circleNumber('3');
}

.four {
  @include circleNumber('4');
}

.five {
  @include circleNumber('5');
}

.six {
  @include circleNumber('6');
}

.equivalent {
  &:after {
    content: '';
    position: absolute;
    top: -1rem;
    right: -0.3rem;
    width: 0;
    height: 0;
    border-left: 0.5rem solid transparent;
    border-right: 0.5rem solid transparent;
    border-bottom: 1.9rem solid $indigo600;
  }
}
</style>
