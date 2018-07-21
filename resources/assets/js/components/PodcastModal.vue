<template>
  <div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title" v-if="podcast.title">
            <editor
              v-if="editingTitle"
              :value="podcast.title"
              @blur.native="editingTitle = false"
              @keyup.native.escape="editingTitle = false"
              @submit="saveTitle"
              class="form-control"
              enter-submit
            />
            <span @dblclick="editingTitle = true;" v-else>{{ podcast.title }}</span>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="d-flex d-flex-row flex-row mb-2">
            <div class="d-flex w-50 align-items-center">
              <h6 class="text-black-50">Audio</h6>
            </div>
            <div class="d-flex w-50 justify-content-end align-items-center">
              <button class="btn btn-light btn-sm" data-toggle="dropdown">
                &nbsp;&nbsp;<i style="font-size: 20px;" class="icon ion-md-more"></i>&nbsp;&nbsp;
              </button>
              <div class="dropdown-menu">
                <a href ="#" @click.prevent="showConfirmDelete(podcast)" class="dropdown-item">Delete</a>
              </div>
            </div>
          </div>
          <audio controls v-if="podcast.resource" style="width: 100%;">
            <source :src="podcast.resource" type="audio/mpeg">
          </audio>
          <h6 class="text-black-50 mt-3 mb-2">Author</h6>
          <p v-if="podcast.author">{{ podcast.author }}</p>
          <img :src="podcast.thumbnail" v-if="podcast.thumbnail" class="img-thumbnail">
          <h6 class="text-black-50 mt-3 mb-2">URL</h6>
          <a :href="podcast.url" target="_blank" v-if="podcast.description" style="white-space: pre-line">{{ podcast.url }}</a>
          <h6 class="text-black-50 mt-3 mb-2">Description</h6>
          <editor
            v-if="editingDescription"
            :value="podcast.description"
            @blur.native="editingDescription = false"
            @keyup.native.escape="editingDescription = false"
            @submit="saveDescription"
            class="form-control"
          />
          <p v-else @dblclick="editingDescription = true" style="white-space: pre-line">{{ podcast.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Editor from './Editor.vue'

export default {
  components: {Editor},

  props: {
    podcast: {required: true}
  },

  data () {
    return {
      editingTitle: false,
      editingDescription: false,
    }
  },

  mounted () {
    $(this.$el).on('hidden.bs.modal', () => {
      this.$emit('close')
    })
  },

  methods: {
    show () {
      $(this.$el).modal('show')
    },

    hide () {
      $(this.$el).modal('hide')
    },

    showConfirmDelete () {
      if (confirm('Are you sure you want to delete this podcast, is can not be undone!')) {
        axios.delete(`/podcasts/${this.podcast.id}`)
          .then(({data}) => {
            this.hide();
            window.events.$emit('PodcastDeleted', this.podcast)
            flash('Podcast successfully deleted', 'info')
          })
          .catch((error) => {
            console.error('error deleting podcast', error)
          })
      }
    },

    saveTitle (title) {
      this.editingTitle = false
      axios.put(`/podcasts/${this.podcast.id}`, {title: title})
        .then(() => {
          this.podcast.title = title
        })
        .catch((error) => {
          console.error('error updating the podcast', error)
          window.error('The was an error updating the podcast title')
        })
    },

    saveDescription (description) {
      this.editingDescription = false
      axios.put(`/podcasts/${this.podcast.id}`, {description: description})
        .then(() => {
          this.podcast.description = description
        })
        .catch((error) => {
          console.error('error updating the podcast', error)
          error('The was an error updating the podcast description')
        })
    },
  }
}
</script>

