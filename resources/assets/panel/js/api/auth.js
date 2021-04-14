import request from '../utils/request'

export function loginWithUsername(data) {
	return request({
		url: 'login',
		method: 'post',
		data
	})
}

export function forgotPassword(data) {
	return request({
		url: 'password/forgot',
		method: 'post',
		data
	})
}

export function resetPassword(data) {
	return request({
		url: 'password/reset',
		method: 'put',
		data
	})
}

export function findToken(token) {
	return request({
		url: `password/find/${token}`,
		method: 'get'
	})
}

export function updateInfo(data) {
	return request({
		url: 'profile',
		method: 'patch',
		data
	})
}

export function updatePassword(data) {
	return request({
		url: 'profile/password',
		method: 'patch',
		data
	})
}

export function getInfo(token) {
	return request({
		url: 'profile',
		method: 'get',
	})
}

export function logout() {
	return request({
		url: 'logout',
		method: 'post'
	})
}
