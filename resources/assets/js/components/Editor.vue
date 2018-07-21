<template>
  <div
    :contenteditable="editable"
    @keydown.enter="submit($event)"
    @keydown="handleCmdEnter($event)"
    v-html="parseHtml(content)"
    @keyup="onChange($event)"
    style="position: display-block; width: 100%"
    @focus="$emit('focus', $event)"
    @blur="$emit('blur', $event)"
  >{{ content }}</div>
</template>

<script>
export default {
  props: {
    editable: {
      default: true
    },
    value: {
      default: ''
    },
    enterSubmit: {
      default: false,
      type: Boolean
    }
  },

  data () {
    return {
      content: this.value
    }
  },

  mounted () {
    this.selectContent()
  },

  methods: {
    submit (e) {
      if (this.enterSubmit) {
        this.$emit('submit', e.target.innerText, e)
      }
    },

    onChange (e) {
      this.$emit('change', e.target.innerText)
      this.$emit('input', e.target.innerText)
    },

    clear () {
      this.$el.innerHTML = ''
    },

    setValue (value) {
      this.content = value
    },

    getValue () {
      return this.$el.innerText
    },

    selectContent () {
      window.Vue.nextTick(() => {
        let selection = window.getSelection()
        let range = document.createRange()

        $(this.$el).focus()
        range.selectNodeContents(this.$el)
        selection.removeAllRanges()
        selection.addRange(range)
      })
    },

    handleCmdEnter (e) {
      if ((e.metaKey || e.ctrlKey) && e.keyCode === 13) {
        this.$emit('submit', e.target.innerText, e)
      }
    },

    parseHtml(content) {
      return content.replace(/\n/g, '<br>')
    }
  }
}
</script>

<style>
  [contenteditable]:focus {
    outline: none;
  }

  [contenteditable=true]:empty:before, .text-container:empty:before {
    content: attr(placeholder);
    color: #999;
    display: block; /* For Firefox */
  }
</style>
