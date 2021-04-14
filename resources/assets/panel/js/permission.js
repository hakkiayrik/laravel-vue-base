import router from './router'
import store from './store'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { getToken } from './utils/auth'
import getPageTitle from './utils/get-page-title'

NProgress.configure({ showSpinner: false, easing: 'ease', speed: 500 })

const whiteList = ['/login', '/forgot-password', '/reset/password/', '/auth-redirect']

router.beforeEach(async(to, from, next) => {
  NProgress.start()
  document.title = getPageTitle(to.meta.title)
  const hasToken = getToken()

  if (hasToken) {
    if (to.path === '/login') {
      next({ path: '/' })
      NProgress.done()
    } else {
      const hasPermissions = store.getters.permissions && store.getters.permissions.length > 0
      if (hasPermissions) {
        next()
      } else {
        try {
          const { permissions } = await store.dispatch('user/getInfo')
          const accessRoutes = await store.dispatch('permission/generateRoutes', permissions)

          router.addRoutes(accessRoutes)
          next({ ...to, replace: true })
        } catch (error) {
          await store.dispatch('user/resetToken')
          next(`/login?redirect=${to.path}`)
          NProgress.done()
        }
      }
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1 || to.path.indexOf("/reset/password/") !== -1 ) {
      next()
    } else {
      next(`/login?redirect=${to.path}`)
      NProgress.done()
    }
  }
})

router.afterEach(() => {
  NProgress.done()
})
