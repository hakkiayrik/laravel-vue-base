import request from '../utils/request'

export function getMedia(query) {
	return request({
		url: 'media',
		method: 'get',
	})
}

export function uploadMedia(data) {
	return request({
		url: `media`,
		method: 'post',
		data
	})
}

export function uploadMediaWithProgress(requestData){
	return request(requestData)
}

export function deleteMedia(id) {
	return request({
		url: `media/${id}`,
		method: 'delete'
	})
}