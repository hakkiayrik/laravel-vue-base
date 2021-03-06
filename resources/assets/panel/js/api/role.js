import request from '../utils/request'

export function getRoles(query) {
  return request({
    url: 'role',
    method: 'get',
    params: query
  })
}

export function getRole(id) {
  return request({
    url: `role/${id}`,
    method: 'get'
  })
}

export function addRole(data) {
  return request({
    url: 'role',
    method: 'post',
    data
  })
}

export function updateRole(id, data) {
  return request({
    url: `role/${id}`,
    method: 'patch',
    data
  })
}

export function deleteRole(id) {
  return request({
    url: `role/${id}`,
    method: 'delete'
  })
}
