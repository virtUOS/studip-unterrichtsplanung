import Vue from 'vue'
import AdminApp from './AdminApp.vue'

Vue.config.productionTip = false

new Vue({
  render: h => h(AdminApp),
}).$mount('#admin-app')
