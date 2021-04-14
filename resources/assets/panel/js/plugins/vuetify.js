import Vue from 'vue'
import Vuetify from 'vuetify'
import i18n from "../lang";
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
	icons: {
		iconfont: 'md',
	},
	lang: {
		t: (key, ...params) => i18n.t(key, params),
	},
}

export default new Vuetify(opts)