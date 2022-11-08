/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('home-component', require('./components/Home-Component.vue').default);
Vue.component('search-component', require('./components/Search-Component.vue').default);
Vue.component('profile-component', require('./components/Profile-Component.vue').default);
Vue.component('photo-component', require('./components/Photo-Component.vue').default);
Vue.component('evaluation-component', require('./components/Evaluation-Component.vue').default);
Vue.component('user-edit-component', require('./components/Evaluation-Component.vue').default);
Vue.component('login-component', require('./components/Login-Component.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 import VueGoogleMap from 'vuejs-google-maps'
 import 'vuejs-google-maps/dist/vuejs-google-maps.css'

 Vue.use(VueGoogleMap, {
     load: {
         apiKey: 'AIzaSyBuBk5ghbTdpdm_nBWg6xHEzdRXdryK6rU',
         libraries: ['places']
     },
     installComponents: true
 })

const app = new Vue({
    el: '#app',
});
