<template>
	<v-app id="inspire">
		<sidebar class="sidebar-container"/>
		<navbar />
		<app-main />
		<setting-menu />
		<snackbar />
		<vue-confirm-dialog />
	</v-app>
</template>

<script>
import Snackbar from '../components/SnackBar'
import { AppMain, Navbar, Sidebar, SettingMenu } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState } from 'vuex'

export default {
  name: 'Layout',
  components: {
    AppMain,
	SettingMenu,
    Navbar,
    Sidebar,
	Snackbar
  },
  mixins: [ResizeMixin],
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
    }),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    }
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    }
  }
}
</script>

<style lang="scss" scoped>
	.fixed-header {
		position: fixed!important;
		top: 0!important;
		right: 0!important;
		z-index: 9!important;
		width: calc(100% - 256px)!important;
		transition: width 0.28s;
	}
</style>