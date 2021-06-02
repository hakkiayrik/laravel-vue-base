import Cookies from 'js-cookie'
import {getLanguage} from "../../lang";

const state = {
	sidebar: {
		opened: Cookies.get('sidebarStatus') ? !!+Cookies.get('sidebarStatus') : true,
		withoutAnimation: false,

	},
	language: getLanguage(),
	snackbar: {
		snackbar: false,
		vertical: false,
		text: "",
		timeout: 5000,
	},
}

const mutations = {
	TOGGLE_SIDEBAR: state => {
		state.sidebar.opened = !state.sidebar.opened
		state.sidebar.withoutAnimation = false
		if (state.sidebar.opened) {
			Cookies.set('sidebarStatus', 1)
		} else {
			Cookies.set('sidebarStatus', 0)
		}
	},
	CLOSE_SIDEBAR: (state, withoutAnimation) => {
		Cookies.set('sidebarStatus', 0)
		state.sidebar.opened = false
		state.sidebar.withoutAnimation = withoutAnimation
	},
	SET_SNACKBAR: (state, payload) => {
		state.snackbar.text = payload.text
		state.snackbar.vertical = (payload.text.length > 50) ? true : false

		if (payload.multiline) {
			state.snackbar.multiline = payload.multiline
		}

		if(payload.color) {
			state.snackbar.color = payload.color
		}

		if(payload.timeout) {
			state.snackbar.timeout = payload.timeout
		}

		state.snackbar.snackbar = true
	},
	TOGGLE_DEVICE: (state, device) => {
		state.device = device
	},
	SET_SIZE: (state, size) => {
		state.size = size
	},
	SET_LANGUAGE: (state, lang) => {
		Cookies.set('language', lang)
		state.language = lang
	}
}

const actions = {
	toggleSideBar({commit}) {
		commit('TOGGLE_SIDEBAR')
	},
	closeSideBar({commit}, {withoutAnimation}) {
		commit('CLOSE_SIDEBAR', withoutAnimation)
	},
	toggleDevice({commit}, device) {
		commit('TOGGLE_DEVICE', device)
	},
	setSize({commit}, size) {
		commit('SET_SIZE', size)
	},
	setSnackBar({commit}, payload) {
		commit('SET_SNACKBAR', payload)
	},
	setLanguage({commit}, payload) {
		commit('SET_LANGUAGE', payload)
	}
}

export default {
	namespaced: true,
	state,
	mutations,
	actions
}
