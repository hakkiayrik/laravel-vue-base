<template>
	<div class="breadcrumbs-container">
		<scroll-pane>
			<v-breadcrumbs :items="levelList" divider="/">
				<template v-slot:item="{item}">
					<v-breadcrumbs-item v-if="item.meta.title" class="no-redirect">
						<span v-if="item.redirect==='noRedirect'">{{ $t('menus.' + item.meta.title) }}</span>
						<router-link v-else ref="tag" :to="item.redirect||item.path">{{ $t('menus.' + item.meta.title) }}</router-link>
					</v-breadcrumbs-item>
				</template>
			</v-breadcrumbs>
		</scroll-pane>
	</div>
</template>

<script>
import ScrollPane from "../ScrollPane";
export default {
	components: {ScrollPane},
	data() {
    return {
      levelList: null
    }
  },
  watch: {
    $route(route) {
      // if you go to the redirect page, do not update the breadcrumbs
      if (route.path.startsWith('/redirect/')) {
        return
      }
      this.getBreadcrumb()
    }
  },
  created() {
    this.getBreadcrumb()
  },
  methods: {
    getBreadcrumb() {
      // only show routes with meta.title
      let matched = this.$route.matched.filter(item => item.meta && item.meta.title)
      const first = matched[0]

      if (!this.isDashboard(first)) {
        matched = [{ path: '/dashboard', meta: { title: 'dashboard' }}].concat(matched)
      }

      this.levelList = matched.filter(item => item.meta && item.meta.title && item.meta.breadcrumb !== false)
    },
    isDashboard(route) {
      const name = route && route.name
      if (!name) {
        return false
      }
      return name.trim().toLocaleLowerCase() === 'Dashboard'.toLocaleLowerCase()
    },
  }
}
</script>

<style lang="scss">
	.v-breadcrumbs__item {
		a {
			text-decoration: none;
			color:#fff!important;
		}
	}
</style>
