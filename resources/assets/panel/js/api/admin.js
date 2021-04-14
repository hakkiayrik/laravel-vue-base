import request from '../utils/request'

export function getAdmins(query) {
	return request({
		url: 'admin',
		method: 'get',
		params: query
	})
}

export function getAdmin(id) {
	return request({
		url: `admin/${id}`,
		method: 'get'
	})
}

export function createAdmin(data) {
	return request({
		url: `admin`,
		method: 'post',
		data
	})
}

export function updateAdmin(id, data) {
	return request({
		url: `admin/${id}`,
		method: 'patch',
		data
	})
}

export function deleteAdmin(id) {
	return request({
		url: `admin/${id}`,
		method: 'delete'
	})
}