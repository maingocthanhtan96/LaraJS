<template>
  <div class="el-upload__file">
    <el-upload
      v-if="!valueComputed"
      action="#"
      class="thumbnail-single"
      name="thumbnail"
      list-type="picture-card"
      drag
      :show-file-list="false"
      :auto-upload="false"
      :accept="accept"
      :on-change="handleUpload"
    >
      <i class="el-icon-plus"></i>
    </el-upload>
    <vue-hover-mask v-if="valueComputed">
      <img v-if="typeFile === 'image'" :src="valueComputed" alt="Image" />
      <video v-else :autoplay="false" :src="valueComputed">Browser not support</video>
      <template #action>
        <span class="ele-upload-video_mask__item" @click="isShowVideo = true">
          <i class="el-icon-zoom-in"></i>
        </span>
        <span class="ele-upload-video_mask__item" @click="handleDelete">
          <i class="el-icon-delete"></i>
        </span>
      </template>
    </vue-hover-mask>
    <el-dialog :visible.sync="isShowVideo" append-to-body>
      <video v-if="isShowVideo" :autoplay="true" :src="value" controls="controls" style="width: 100%">
        Browser not support
      </video>
    </el-dialog>
  </div>
</template>

<script>
import VueHoverMask from 'vue-hover-mask/src/index';
export default {
  components: {
    VueHoverMask,
  },
  props: {
    value: {
      type: String,
      default: '',
    },
    accept: {
      type: String,
      default: 'video/*,image/png,image/gif,image/jpg,image/jpeg',
    },
    fileSize: {
      type: Number,
      default: 500,
    },
    dimensions: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      isShowVideo: false,
      typeFile: 'image', // image or video
    };
  },
  computed: {
    valueComputed: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit('input', val);
      },
    },
  },
  mounted() {
    if (this.value) {
      const fileExtension = this.value.substr(this.value.lastIndexOf('.') + 1);
      if (fileExtension.match(/^(jpg|jpeg|png|gif)/i)) {
        this.typeFile = 'image';
      } else {
        this.typeFile = 'video';
      }
    }
  },
  methods: {
    async handleUpload(file) {
      this.typeFile = 'video';
      const imageExt = ['image/gif', 'image/jpeg', 'image/png'];
      if (imageExt.includes(file.raw.type)) {
        this.typeFile = 'image';
      }

      let isFormat = this.accept.includes(file.raw.type);
      if (this.typeFile === 'video') {
        isFormat = this.accept.includes('video');
      }
      if (!isFormat) {
        this.$message.error(this.$t('messages.format_incorrect'));
        return false;
      }
      if (this.fileSize) {
        const isLt = file.size / 1024 / 1024 <= this.fileSize;
        if (!isLt) {
          this.$message.error(`Upload video size cannot exceed${this.fileSize}MB!`);
          return false;
        }
      }
      if (this.dimensions) {
        const isDimension = await this.checkPixel(file, this.dimensions);
        if (!isDimension) {
          this.$message.error(this.$t('messages.format_dimension', { attribute: this.dimensions }));
          return false;
        }
      }
      this.valueComputed = URL.createObjectURL(file.raw);
      this.$emit('change', file);
    },
    handleDelete() {
      this.valueComputed = '';
      this.$emit('remove', this.valueComputed);
    },
    checkPixel(file, dimensions) {
      return new Promise((resolve, reject) => {
        let isDimension = false;
        const img = new Image();
        img.src = URL.createObjectURL(file.raw);
        img.onload = function () {
          const width = img.naturalWidth;
          const height = img.naturalHeight;
          const [widthRule, heightRule] = dimensions.split('x');
          if (width === +widthRule && height === +heightRule) {
            isDimension = true;
          }
          resolve(isDimension);
        };
        img.onerror = reject;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep {
  .el-upload-dragger {
    width: 100%;
    height: 100%;
  }
}
</style>
