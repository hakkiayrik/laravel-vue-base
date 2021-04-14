import i18n from "../lang"

const title = process.env.MIX_PANEL_TITLE || 'Admin Panel'

export default function getPageTitle(key) {
  const hasKey = i18n.te(`menus.${key}`)

  if (hasKey) {
    const pageTitle = i18n.t(`menus.${key}`)
    return `${pageTitle} - ${title}`
  }
  return `${title}`
}
