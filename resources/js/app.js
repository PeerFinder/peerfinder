require('./bootstrap');

import Vue from 'vue';

Vue.component('header-menu', require('./components/HeaderMenu.vue').default);

const app = new Vue({
    el: '#app'
});