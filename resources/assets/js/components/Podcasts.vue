<template>
  <div>
    <div class="list-group">
      <a v-for="podcast in list" @click.prevent="showPodcastModal(podcast)" :key="podcast.id" href="#" class="list-group-item list-group-item-action d-flex flex-row">
        <div class="w-30 justify-content-center align-items-center">
          <img :src="podcast.thumbnail" alt="" v-if="podcast.thumbnail" class="img-thumbnail mr-3" style="max-width: 150px;">
        </div>
        <div class="w-70">
          <div class="d-flex w-100 d-flex-row justify-content-between">
            <h5 class="mb-1">{{ podcast.title }}</h5>
            <small :title="podcast.updated_at">{{ podcast.updated_at | fromNow }}</small>
          </div>
          <p>{{ podcast.author }}</p>
          <p class="mb-1">{{ podcast.description.substring(0, 200) }}...</p>
        </div>
      </a>
      <div class="list-group-item" v-if="list.length === 0">
        <center><h5>No podcast added yet.</h5></center>
      </div>
      <podcast-modal ref="modal" @close="selectedPodcast = {}" :podcast="selectedPodcast"></podcast-modal>
    </div>
    <div class="d-flex justify-content-center pt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination" v-show="lastPage !== 1">
          <li class="page-item" :class="page === 1 ? 'disabled' : ''">
            <a class="page-link" href="#" @click.prevent="prevPage">Previous</a>
          </li>
          <li class="page-item" :class="page === lastPage ? 'disabled' : ''">
            <a class="page-link" href="#" @click.prevent="nextPage">Next</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import PodcastModal from './PodcastModal.vue'

export default {
  components: {PodcastModal},

  props: {
    limit: {default: 15},
    page: {default: 1}
  },

  data () {
    return {
      list: [],
      selectedPodcast: {},
      lastPage: 1
    }
  },

  created () {
    window.events.$on('YoutubeDownloadCompleted', this.getList)
    window.events.$on('PodcastDeleted', this.getList)
    this.getList();
  },

  methods: {
    getList () {
      let query = `?page=${this.page}&limit=${this.limit}`
      window.history.pushState('', '', query)
      window.axios.get(`/podcasts${query}`)
        .then(({data}) => {
          this.list = data.data
          this.currentPage = data.current_page
          this.lastPage = data.last_page
        })
        .catch((error) => {
          console.error('error downloading list', error)
        })
    },

    showPodcastModal (podcast) {
      this.selectedPodcast = podcast
      this.$refs.modal.show()
    },

    nextPage () {
      this.page = this.page + 1
      this.getList()
    },

    prevPage () {
      this.page = this.page - 1
      this.getList()
    }
  }
}
</script>

