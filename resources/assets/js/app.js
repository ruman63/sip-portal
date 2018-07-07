
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import VModal from 'vue-js-modal';
import Vuex from 'vuex';

import Vue from 'vue';
Vue.use(Vuex);
Vue.use(VModal);

window.Events = new Vue({});



String.prototype.currency = function(){
    return this.replace(/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/g, '$1,');
}

Array.prototype.groupBy = function (callback) {
    var grouped = {};
    this.forEach(function(item) {
        let key = callback(item);
        if(grouped.hasOwnProperty(key)) {
            grouped[key].push(item);
        } else {
            grouped[key] = [item]
        }
    });
    return grouped;
}

Vue.filter('currency', function(number) {
    if(typeof(number) !== 'String') {
        number = number.toString();
    }
    return number.currency();
});

Vue.filter('fixed', function(number) {
    if(typeof(number) != 'Number') {
        number = parseFloat(number);
    }
    return number.toFixed(2);
});

Vue.component('transactions-page', require('./pages/TransactionsPage.vue'));
Vue.component('manage-sip-page', require('./pages/ManageSipPage.vue'));
Vue.component('schemes-view', require('./pages/SchemesView.vue'));
Vue.component('client-profile-view', require('./pages/ClientProfileView.vue'));

Vue.component('import-csv-data', require('./components/ImportCsvData.vue'));
Vue.component('dropdown', require('./components/Dropdown.vue'));
Vue.component('expandable-list-item', require('./components/ExpandableListItem.vue'));
Vue.component('v-data-table', require('./components/VueDataTable.vue'));
Vue.component('v-ajax-form', require('./components/VueAjaxForm.vue'));
Vue.component('v-typeahead', require('./components/Typeahead.vue'));
Vue.component('logout', require('./components/Logout.vue'));
Vue.component('clock', require('./components/Clock.vue'));
Vue.component('chart', require('./components/Chart.vue'));
Vue.component('flash', require('./components/Flash.vue'));

window.flash = (message, level="success", important=false) => {
    Events.$emit('flash', {
        id: Math.floor(Math.random()*10),
        message, level, important
    });
window.filterObject = (obj, allowed) => Object.keys(obj)
    .filter(key => allowed.includes(key))
    .reduce((newObj, key) => ({...newObj, ...{[key]: obj[key]}}), {} );

const app = new Vue({
    el: '#app',
    methods: {
        flash: window.flash
    }
});