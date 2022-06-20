import request from '../utils/request'

export function getPosts(query) {
	return request({
		url: 'post',
		method: 'get',
		params: query
	})
}

export function getPost(id) {
	return request({
		url: `post/${id}`,
		method: 'get'
	})
}

export function createPost(data) {
	return request({
		url: `post`,
		method: 'post',
		data
	})
}

export function updatePost(id, data) {
	return request({
		url: `post/${id}`,
		method: 'post',
		data
	})
}

export function deletePost(id) {
	return request({
		url: `post/${id}`,
		method: 'delete'
	})
}
