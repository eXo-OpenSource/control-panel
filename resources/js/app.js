
require('./bootstrap');

window.Vue = require('vue');

Vue.component('chart-component', require('./components/ChartComponent.vue').default);

const app = new Vue({
    el: '#app'
});