const getters = {
  sidebar: state => state.app.sidebar,
  snackbar: state => state.app.snackbar,
  language: state => state.app.language,
  size: state => state.app.size,
  device: state => state.app.device,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  user: state => state.user.user,
  introduction: state => state.user.introduction,
  permissions: state => state.user.permissions,
  permission_routes: state => state.permission.routes,
}
export default getters
