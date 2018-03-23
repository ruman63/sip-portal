
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VModal from 'vue-js-modal';
window.Vue = require('vue');
window.Vue.use(VModal);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('dropdown', require('./components/Dropdown.vue'));
Vue.component('expandable-list-item', require('./components/ExpandableListItem.vue'));
Vue.component('v-data-table', require('./components/VueDataTable.vue'));
Vue.component('logout', require('./components/Logout.vue'));
Vue.component('clock', require('./components/Clock.vue'));
Vue.component('flash', require('./components/Flash.vue'));

const app = new Vue({
    el: '#app'
});
