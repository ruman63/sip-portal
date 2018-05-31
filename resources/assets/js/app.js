
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VModal from 'vue-js-modal';
window.Vue = require('vue');
window.Vue.use(VModal);
window.Events = new Vue({});



String.prototype.currency = function(){
    return this.replace(/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/g, '$1,');
}

Vue.filter('currency', function(number) {
    if(typeof(number) !== 'String') {
        number = number.toString();
    }
    return number.currency();
});

Vue.component('dropdown', require('./components/Dropdown.vue'));
Vue.component('expandable-list-item', require('./components/ExpandableListItem.vue'));
Vue.component('v-data-table', require('./components/VueDataTable.vue'));
Vue.component('folio-entry-form', require('./components/FolioEntryForm.vue'));
Vue.component('v-typeahead', require('./components/Typeahead.vue'));
Vue.component('logout', require('./components/Logout.vue'));
Vue.component('clock', require('./components/Clock.vue'));
Vue.component('chart', require('./components/Chart.vue'));
Vue.component('flash', require('./components/Flash.vue'));

const app = new Vue({
    el: '#app'
});

window.flash = (message, level="success", important=false) => {
    Events.$emit('flash', {
        id: Math.floor(Math.random()*10),
        message, level, important
    });
}