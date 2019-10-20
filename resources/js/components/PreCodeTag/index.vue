<template>
  <pre class='code code-php'>
    <code v-html="content"></code>
  </pre>
</template>

<script>
export default {
  name: 'PreCodeTag',
  props: {
    content: {
      type: String,
      required: false,
      default: '',
    },
  },
};
</script>

<style lang="scss" scoped>
  /* CSS Simple Pre Code */
  pre {
    font-size: 1.5rem;
    border: 1px solid grey;
    width: 60vw;
    max-width: 700px;
    border-left: 12px solid #67C23A;
    border-radius: 5px;
    padding: 14px;
    white-space: pre;
    word-wrap: break-word;
    overflow: auto;

    code {
      font-family: "Inconsolata", "Monaco", "Consolas", "Andale Mono", "Bitstream Vera Sans Mono", "Courier New", Courier, monospace;
      color: #dd6b20;
    }
  }
</style>
