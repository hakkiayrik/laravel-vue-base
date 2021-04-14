import request from "../utils/request";

export function getLogs(query) {
	return request({
		url: 'log',
		method: 'get',
		params: query
	})
}