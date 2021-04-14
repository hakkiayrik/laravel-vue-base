import axios from 'axios'
import store from '../store'
import i18n from "../lang";
import {getToken} from './auth'
import Cookies from 'js-cookie'

// create an axios instance
const service = axios.create({
	baseURL: process.env.MIX_PANEL_BASE_API_URL,
	timeout: 1000 * 60 * 5, // request timeout
	headers: {
		'Content-Type': 'application/json'
	}
})

// request interceptor
service.interceptors.request.use(config => {
		//language
		config.headers['Language'] = Cookies.get('language')

		if (store.getters.token) {
			if (store.getters.token) {
				config.headers['Authorization'] = 'Bearer ' + getToken()
			}
		}
		return config
	},
	error => {
		// do something with request error
		console.log(error) // for debug
		return Promise.reject(error)
	}
)

// response interceptor
service.interceptors.response.use(
	response => {
		const res = response.data
		const message = res.message ?? ''

		if (message.length > 0) {
			store.dispatch('app/setSnackBar', {text: res.message, color: 'success'})
		}

		return res
	},
	error => {
		if(error.response.status === 403) {
			store.dispatch('app/setSnackBar', {text: error.response.data.message, color: 'error'})
		}
		if(error.response.status === 500) {
			store.dispatch('app/setSnackBar', {text: i18n.t('messages.error.error_500'), color: 'error'})
		}

		return Promise.reject(error)
	}
)

export default service
