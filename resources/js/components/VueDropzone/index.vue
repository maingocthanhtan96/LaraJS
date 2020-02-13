<template>
  <vue-dropzone
    :id="id"
    :ref="id"
    :options="options"
    :destroy-dropzone="false"
    @vdropzone-file-added="addFile"
    @vdropzone-removed-file="removeFile"
    @vdropzone-success="success"
  />
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import { getToken } from '@/utils/auth';

const token = getToken() || '';
export default {
  name: 'VueDropzone',
  components: {
    vueDropzone: vue2Dropzone
  },
  props: {
    options: {
      type: Object,
      default() {
        return {
          url: `${process.env.MIX_BASE_API}/upload-file/store`,
          maxFilesize: 10,
          addRemoveLinks: true,
          dictDefaultMessage:
            "<i class='el-icon-upload text-5xl'></i>" +
            '<br>Drop files here to upload',
          maxFiles: 10,
          headers: {
            Authorization: 'Bearer ' + token
          }
        };
      }
    },
    id: {
      type: String,
      required: true
    },
    defaultImg: {
      type: [String, Array],
      default() {
        return [];
      }
    }
  },
  data() {
    return {
      initOnce: true
    };
  },
  watch: {
    defaultImg(val) {
      if (val.length === 0) {
        this.initOnce = false;
        return;
      }
      if (!this.initOnce) {
        return;
      }
      this.initImages(val);
      this.initOnce = false;
    }
  },
  methods: {
    addFile(file) {
      this.$emit('addedFile', file);
    },
    removeFile(file, error, xhr) {
      this.$emit('removedFile', file, error, xhr);
    },
    success(file, response) {
      this.$emit('success', file, response);
    },
    initImages(val) {
      const ref = this.id;
      if (!Array.isArray(val)) {
        val = JSON.parse(val);
      }
      val.map((value, index) => {
        if (value) {
          this.$refs[ref].manuallyAddFile(
            { size: 12345, name: 'Image ' + (index + 1), nameRemove: value },
            value
          );
        }
        return true;
      });
    }
  },
  destroy() {
    this.$destroy();
  }
};
</script>

<style lang="scss"></style>
