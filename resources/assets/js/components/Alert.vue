<template>
  <div>
    <transition name="fade">
      <div class="alert alert-fixed fade show" v-show="show" :class="`alert-${severity}`">
        {{ message }}
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  data () {
    return {
      severity: 'success',
      show: false,
      message: null,
    }
  },

  created () {
    window.events.$on('flash', (message, severity = 'success', timeout = 5000) => this.showMessage(message, severity, timeout))
    window.events.$on('error', (message, timeout = 5000) => this.showMessage(message, 'danger', timeout))
  },

  methods: {
    showMessage (message, severity, timeout = 5000) {
      this.message = message
      this.severity = severity
      this.show = true
      setTimeout(() => this.show = false, timeout)
    }
  }
}
</script>

<style>
.alert-fixed {
  position: fixed;
  bottom: 0px;
  right: 15px;
  z-index: 99999;
  box-shadow: 1px 1px 2px rgba(0, 0, 0, 15%);
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>

