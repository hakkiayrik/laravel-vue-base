import Vue from 'vue'
import './plugins/vee-validate'
import './plugins/json-viewer'
import App from './App.vue'
import store from './store'
import router from './router'

import i18n from './lang'
import './permission'
import vuetify from './plugins/vuetify'
import permission from './directive/permission/index.js'
import VueConfirmDialog from 'vue-confirm-dialog'

import * as filters from './filters'

Vue.use(VueConfirmDialog)
Vue.component('vue-confirm-dialog', VueConfirmDialog.default)

permission.install(Vue)

Vue.config.productionTip = false

Vue.config.productionTip = process.env.NODE_ENV === 'production'

// register global utility filters
Object.keys(filters).forEach(key => {
	Vue.filter(key, filters[key])
})

new Vue({
	router,
	store,
	i18n,
	vuetify,
	render: h => h(App)
}).$mount('#app')
