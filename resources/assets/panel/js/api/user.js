import request from '../utils/request'

export function getUsers(query) {
  return request({
    url: 'user',
    method: 'get',
    params: query
  })
}

export function getUser(id) {
  return request({
    url: `user/${id}`,
    method: 'get'
  })
}

export function updateUser(id, data) {
  return request({
    url: `user/${id}`,
    method: 'patch',
    data
  })
}


export function getUserLogs(id) {
  return request({
    url: `user/log/${id}`,
    method: 'get'
  })
}

export function deleteUser(id) {
  return request({
    url: `user/${id}`,
    method: 'delete'
  })
}