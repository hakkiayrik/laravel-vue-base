import Cookies from 'js-cookie'
import defaultSettings from '../../settings'

const { tagsView, fixedHeader, darkTheme} = defaultSettings
console.log(!!+Cookies.get('darkTheme'))
const state = {
  tagsView: tagsView,
  fixedHeader: fixedHeader,
  darkTheme: Cookies.get('darkTheme') ? !!+Cookies.get('darkTheme') : darkTheme,
}

const mutations = {
  CHANGE_SETTING: (state, { key, value }) => {
    // eslint-disable-next-line no-prototype-builtins
    if (state.hasOwnProperty(key)) {
      state[key] = value
    }
  }
}

const actions = {
  changeSetting({ commit }, data) {
    commit('CHANGE_SETTING', data)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

