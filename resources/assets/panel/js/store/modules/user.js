import {loginWithUsername, resetPassword, logout, updateInfo, updatePassword, getInfo} from '../../api/auth'
import {getToken, setToken, removeToken} from '../../utils/auth'
import {resetRouter} from '../../router'

const state = {
	token: getToken(),
	user: {
		institution: {}
	},
	permissions: []
}

const mutations = {
	SET_TOKEN: (state, token) => {
		state.token = token
	},
	SET_USER: (state, payload) => {
		state.user = payload
	},
	SET_PERMISSIONS: (state, permissions) => {
		state.permissions = permissions
	}
}

const actions = {
	// user login
	login({commit}, userInfo) {
		const {username, password} = userInfo
		return new Promise((resolve, reject) => {
			loginWithUsername({username: username.trim(), password: password}).then(response => {
				const {data} = response
				commit('SET_TOKEN', data.token)
				setToken(data.token)
				resolve()
			}).catch(error => {
				reject(error)
			})
		})
	},

	resetPassword({commit}, payload) {
		const 	{oldPassword, newPassword} = payload
		return new Promise( (resolve, reject) => {
			resetPassword({old_password: oldPassword, new_password: newPassword}).then(response => {
				console.log(response)
			})
		})
	},

	updateInfo({commit}, payload) {
		return new Promise((resolve, reject) => {
			updateInfo(payload).then(response => {
				commit('SET_USER', payload)
				resolve()
			}).catch(error => {
				reject(error)
			})
		})
	},

	updatePassword({commit}, payload) {
		return new Promise((resolve, reject) => {
			updatePassword(payload).then(response => {
				resolve()
			}).catch(error => {
				reject(error)
			})
		})
	},

	// get user info
	getInfo({commit, state}) {
		return new Promise((resolve, reject) => {
			getInfo(state.token).then(response => {
				const {data} = response

				if (!data) {
					reject('Verification failed, please Login again.')
				}

				const {permissions} = data.user.role

				// permissions must be a non-empty array
				if (!permissions || permissions.length <= 0) {
					reject('getInfo: permissions must be a non-null array!')
				}

				commit('SET_USER', data.user)
				commit('SET_PERMISSIONS', permissions)
				resolve(data.user.role)
			}).catch(error => {
				reject(error)
			})
		})
	},

	// user logout
	logout({commit, state, dispatch}) {
		return new Promise((resolve, reject) => {
			logout(state.token).then(() => {
				commit('SET_TOKEN', '')
				commit('SET_PERMISSIONS', [])
				removeToken()
				resetRouter()

				// reset visited views and cached views
				// to fixed https://github.com/PanJiaChen/vue-element-admin/issues/2485
				dispatch('tagsView/delAllViews', null, {root: true})

				resolve()
			}).catch(error => {
				reject(error)
			})
		})
	},

	// remove token
	resetToken({commit}) {
		return new Promise(resolve => {
			commit('SET_TOKEN', '')
			commit('SET_PERMISSIONS', [])
			removeToken()
			resolve()
		})
	},

}

export default {
	namespaced: true,
	state,
	mutations,
	actions
}
