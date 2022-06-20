import request from '../utils/request'

export function getLanguages(query) {
    return request({
        url: 'language',
        method: 'get',
        params: query
    })
}

export function changeActive(id, data) {
    return request({
        url: `language/active/${id}`,
        method: 'post',
        data
    })
}
