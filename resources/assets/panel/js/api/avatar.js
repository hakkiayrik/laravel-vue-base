import request from "../utils/request";

export function getAvatars() {
	return request({
		url: 'avatar',
		method: 'get',
	})
}