
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Global events
 */
window.events = new Vue();

/**
 * Alert events
 */
window.flash = (message = '', severity = 'success', timeout = 5000) => {
  window.events.$emit('flash', message, severity)
}
window.error = (message = '', timeout = 5000) => {
  window.events.$emit('error', message)
}

/**
 * Filters
 */
import FromNow from './filters/FromNow';
Vue.filter('fromNow', FromNow)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('youtube-download', require('./components/YoutubeDownload.vue'));
Vue.component('podcasts', require('./components/Podcasts.vue'));
Vue.component('alert', require('./components/Alert.vue'));

const app = new Vue({
    el: '#app'
});
