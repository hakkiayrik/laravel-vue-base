import permission from './permission'
import checkPermission from "../../utils/permission"

const install = function(Vue) {
  Vue.prototype.$checkPermission = checkPermission

  Vue.directive('permission', permission)
}

if (window.Vue) {
  window['permission'] = permission
  Vue.use(install); // eslint-disable-line
}

permission.install = install
export default permission
