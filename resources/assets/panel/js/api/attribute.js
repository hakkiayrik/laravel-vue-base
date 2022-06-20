import request from '../utils/request'

/* Attribute API Functions */

export function getAttributeGroups(query) {
    return request({
        url: 'attribute/group',
        method: 'get',
        params: query
    })
}

export function getAttributeGroup(id) {
    return request({
        url: `attribute/group/${id}`,
        method: 'get'
    })
}

export function addAttributeGroup(data) {
    return request({
        url: 'attribute/group',
        method: 'post',
        data
    })
}

export function updateAttributeGroup(id, data) {
    return request({
        url: `attribute/group/${id}`,
        method: 'patch',
        data
    })
}

export function deleteAttributeGroup(id) {
    return request({
        url: `attribute/group/${id}`,
        method: 'delete'
    })
}

/* Attribute Functions */
export function getAttributes() {
    return request({
        url: `attribute`,
        method: 'get'
    })
}

export function getAttribute(id) {
    return request({
        url: `attribute/${id}`,
        method: 'get'
    })
}

export function addAttribute(data) {
    return request({
        url: 'attribute',
        method: 'post',
        data
    })
}

export function updateAttribute(id, data) {
    return request({
        url: `attribute/${id}`,
        method: 'patch',
        data
    })
}

export function deleteAttribute(id) {
    return request({
        url: `attribute/${id}`,
        method: 'delete'
    })
}


export function assignAttributes(id, data) {
    return request({
        url: `attribute/add/group/${id}`,
        method: 'post',
        data
    })
}
