/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from "vue";
import Vuetify from "vuetify";

import '@mdi/font/css/materialdesignicons.css'

require('./bootstrap');

window.Vue = require('vue');

Vue.use(Vuetify);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//initialize component first

// Vue.component(
//     'forum-reply-component',
//     require('./components/ForumReplyComponent.vue')
// );

Vue.component(
    'favorite-reply-component',
    require('./components/FavoriteReplyComponent.vue').default);

Vue.component(
    'replies-component',
    require('./components/RepliesComponent.vue').default);

Vue.component(
    'subscribe-thread-component',
    require('./components/ThreadSubscribeComponenet.vue').default);

Vue.component(
    'thread-component',
    require('./components/ThreadComponent.vue').default);

Vue.component(
    'paginator-component',
    require('./components/PaginatorComponent.vue').default);

Vue.component(
    'user-notification-component',
    require('./components/UserNotificationComponent.vue').default);

Vue.component(
    'wysiwyg-component',
    require('./components/Wysiwyg.vue').default);

Vue.component(
    'wysiwyg-multiple-component',
    require('./components/WysiwygMultiple.vue').default);

Vue.component(
    'threads',
    require('./components/Threads/Threads.vue').default);

Vue.component(
    'left-navigation',
    require('./components/navigation/LeftNavigation').default);

Vue.component(
    'right-navigation',
    require('./components/navigation/RightNavigation').default);

Vue.component(
    'user-profile',
    require('./components/UserProfileComponent').default);

Vue.component(
    'loading-component',
    require('./components/admin/forum/Loader').default);

Vue.component(
    'flashing-component',
    require('./components/admin/FlashComponent').default);

Vue.component(
    'search',
    require('./components/SearchComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


 //register the event listner
window.events = new Vue();

window.flash = function(message, type = 'success') {
    window.events.$emit('flash', {message, type} );
}



const app = new Vue({
    el: '#app',
    data: {

        //======================== this data refers to Shop Appearance data
        // vue was yelling to initialize data even with empty value
        //for details visit: https://vuejs.org/v2/guide/reactivity.html#Declaring-Reactive-Properties
        // declare message with an empty value
        primaryButtonOverrideStyles: {
            backgroundColor: ''
        },

        linkOverrideStyles:{
            color: ''
        },

        headingOverrideStyles: {
            color: ''
        },

        paragraphOverrideStyles: {
            color: ''
        },
        value: ''
        // =======================this data refers to Shop Appearance data  ended
    },
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
});
