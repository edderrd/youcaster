<template>
  <form class="form-group" @submit.prevent="startDownload" novalidate>
    <div class="input-group">
      <input type="text" :disabled="inProgress" :class="error ? 'is-invalid' : ''" v-model="url" placeholder="Youtube URL" class="form-control">
      <div class="input-group-append">
        <input type="submit" :disabled="inProgress" class="btn btn-outline-secondary" value="Download">
      </div>
      <div class="invalid-feedback">{{ error }}</div>
    </div>
    <br>
    <progress-bar :percentage="percentage" :eta="eta" :speed="speed"></progress-bar>
  </form>
</template>
<script>
import ProgressBar from './ProgressBar.vue'

export default {
  components: {ProgressBar},

  data () {
    return {
      url: null,
      error: null,
      percentage: 0,
      eta: null,
      speed: null,
    }
  },

  created () {
    const userId = window.userId
    window.Echo.private(`downloads.${userId}`)
      .listen('YoutubeDownloadProgress', (e) => {
        // console.debug('youtube.download.progress', e)
        this.setProgress(e.progress)
      })
      .listen('YoutubeDownloadCompleted', (e) => {
        this.setCompleted(e)
      })
      .listen('YoutubeDownloadFailed', (e) => {
        flash('There was an error downloading the video')
        this.progress = 0
      })
  },

  computed: {
    inProgress () {
      return parseInt(this.percentage) > 0 && parseInt(this.percentage) < 100
    }
  },

  methods: {
    startDownload() {
      this.error = null
      if (!this.urlValid(this.url)) {
        return this.error = 'Invalid Youtube url given.'
      }
      this.percentage = 1;
      window.axios.post('/podcasts', {
          url: this.url
      }).then(({data}) => {
      }).catch((error) => {
          console.log('error', error)
          this.percentage = 0;
          error('There was an error downloading the video.')
      })
    },

    urlValid(url) {
      const re = new RegExp('^(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$')
      return re.test(url)
    },

    setProgress(progress) {
      this.eta = progress.eta || null
      this.speed = progress.speed || null
      this.percentage = parseFloat(progress.percentage.replace('%', ''))
    },

    setCompleted(podcast) {
      this.url = ''
      this.percentage = 100
      flash('Video downloaded successfully', 'info')
      window.events.$emit('YoutubeDownloadCompleted', podcast)
      // autohide complete message
      setTimeout(() => {
        this.percentage = 0
      }, 5000)
    }
  }
}
</script>
