import request from '../utils/request'

export function getCategories(query) {
	return request({
		url: 'category',
		method: 'get',
		params: query
	})
}

export function getCategory(id) {
	return request({
		url: `category/${id}`,
		method: 'get'
	})
}

export function createCategory(data) {
	return request({
		url: `category`,
		method: 'post',
		data
	})
}

export function updateCategory(id, data) {
	return request({
		url: `category/${id}`,
		method: 'patch',
		data
	})
}

export function deleteCategory(id) {
	return request({
		url: `category/${id}`,
		method: 'delete'
	})
}