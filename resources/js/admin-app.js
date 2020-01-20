 require('./admin-bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'

 import Vuelidate from 'vuelidate'


 import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader
 import ReadMore from 'vue-read-more';
 import VueFusionCharts from 'vue-fusioncharts';
 import FusionCharts from 'fusioncharts';
 import Charts from 'fusioncharts/fusioncharts.charts';
 import TimeSeries from 'fusioncharts/fusioncharts.timeseries';

 //import the theme
 import FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion'

 // register VueFusionCharts component
 Vue.use(VueFusionCharts, FusionCharts, Charts, FusionTheme, TimeSeries)

Vue.use(VueRouter)

Vue.use(Vuelidate)

 import router from './components/admin/router'

import Vuetify from 'vuetify'

Vue.use(Vuetify);

Vue.use(ReadMore);

Vue.component(
    'admin-header',
    require('./components/admin/Header.vue').default);

window.events = new Vue();

window.flash = function(message, type = 'success') {
     window.events.$emit('flash', {message, type} );
}


 Vue.component(
     'flash-component',
     require('./components/admin/FlashComponent.vue').default);

 Vue.component(
     'loader-component',
     require('./components/admin/forum/Loader.vue').default);



 /* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
    //   components: { App },
    //   template: '<App/>'
})


 window.user = function (){ // default will be a success message

     window.events.$emit('user' );
 }
