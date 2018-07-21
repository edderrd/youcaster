<template>
  <div v-show="inProgress">
    <div class="progress">
      <div
        class="progress-bar" :class="inProgress ? 'progress-bar-striped progress-bar-animated' : ''"
        role="progressbar"
        :aria-valuenow="percentage"
        aria-valuemin="0"
        aria-valuemax="100"
        :style="`width: ${percentage}%;`"
      >
        <span>
          {{ percentage }}%
        </span>
      </div>
    </div>
    <div class="small text-info">
    <span v-show="eta">ETA: {{ eta }}</span>
    <span v-show="speed" class="float-right">Speed: {{ speed }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    percentage: {required: true},
    speed: {required: false},
    eta: {required: false}
  },

  computed: {
    inProgress() {
      return parseInt(this.percentage) > 0 && parseInt(this.percentage) < 100;
    }
  }
}
</script>

