import Vue from 'vue'
import Cookies from 'js-cookie'
import VueI18n from 'vue-i18n'
Vue.use(VueI18n)

//define messsages variable
const messages = {}

for (var key in JSON.parse(process.env.MIX_PANEL_LANGUAGES)) {
	messages[key] = {
		...require(`./${key}`),
	}
	messages[key]['$vuetify'] = require(`vuetify/es5/locale/${key}.js`).default;
	messages[key]['validation'] = require(`vee-validate/dist/locale/${key}`).messages;
}

export function getLanguage() {
	const chooseLanguage = Cookies.get('language')
	if (chooseLanguage) return chooseLanguage

	//if language not selected
	const language = (navigator.language || navigator.browserLanguage).toLowerCase()
	const locales = Object.keys(messages)

	for (const locale of locales) {
		if (language.indexOf(locale) > -1) {
			return locale
		}
	}

	return process.env.MIX_DEFAULT_LANGUAGE
}

Cookies.set('language', getLanguage())


const i18n = new VueI18n({
	locale: getLanguage(),
	fallbackLocale: process.env.MIX_DEFAULT_LANGUAGE,
	messages
})

export default i18n