<template>
	<v-app id="inspire">
		<sidebar class="sidebar-container" :class="{'fixed-header':fixedHeader}"/>
		<navbar />
		<app-main />
		<snackbar />
		<vue-confirm-dialog />
	</v-app>
</template>

<script>
import Snackbar from '../components/SnackBar'
import { AppMain, Navbar, Sidebar } from './components'
import ResizeMixin from './mixin/ResizeHandler'
import { mapState } from 'vuex'

export default {
  name: 'Layout',
  components: {
    AppMain,
    Navbar,
    Sidebar,
	Snackbar
  },
  mixins: [ResizeMixin],
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      fixedHeader: state => state.settings.fixedHeader
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