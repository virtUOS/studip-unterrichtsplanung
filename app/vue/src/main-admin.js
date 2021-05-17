import Vue from 'vue'
import AdminApp from './AdminApp.vue'
import store from './store';

Vue.config.productionTip = false

new Vue({
  el: '#admin-app',
  store,
  render: h => h(AdminApp),
});
