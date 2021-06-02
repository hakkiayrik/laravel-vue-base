import Vue from 'vue'
import Vuetify from 'vuetify'
import i18n from "../lang";
import Cookies from 'js-cookie'
import 'vuetify/dist/vuetify.min.css'
import defaultSettings from '../settings'

const {darkTheme} = defaultSettings

Vue.use(Vuetify)
const opts = {
	icons: {
		iconfont: 'md',
	},
	lang: {
		t: (key, ...params) => i18n.t(key, params),
	},
	theme: {
		dark: Cookies.get('darkTheme') ? !!+Cookies.get('darkTheme') : darkTheme,
		themes: {
			dark: {
				primary: '#1976D2',
				secondary: '#424242',
				accent: '#82B1FF',
				error: '#FF5252',
				info: '#2196F3',
				success: '#4CAF50',
				warning: '#FFC107',
			}
		}
	},

}

export default new Vuetify(opts)