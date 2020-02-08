<template>
  <span
    contentEditable="true"
    :class="classes"
    :aria-placeholder="placeholder"
    @input="update"
    @keydown.enter="checkEnter($event)"
  />
</template>

<script>
export default {
  props: {
    placeholder: {
      type: String,
      default() {
        return '';
      }
    },
    classes: {
      type: String,
      default() {
        return '';
      }
    },
    content: {
      type: String,
      default() {
        return '';
      }
    },
    maxlength: {
      type: Number,
      default() {
        return 256;
      }
    },
    enter: {
      type: Boolean,
      default() {
        return true;
      }
    }
  },
  data() {
    return {
      textCurrent: ''
    };
  },
  mounted() {
    this.$el.innerText = this.content;
  },
  methods: {
    update: function(event) {
      if (this.countChars(this.$el.innerText) <= this.maxlength) {
        this.textCurrent = event.target.innerText;
        this.$emit('update', event.target.innerText);
      } else {
        this.$el.innerText = this.textCurrent;
      }
    },
    // count japan
    countChars: function(str) {
      return str.replace(/[\u0080-\u10FFFF]/g, 'x').length;
    },
    checkEnter: function(event) {
      if (!this.enter) {
        return event.preventDefault();
      }
    }
  }
};
</script>

<style lang="scss" scoped>
[contentEditable='true']:empty:not(:focus):before {
  content: attr(aria-placeholder);
}
</style>
