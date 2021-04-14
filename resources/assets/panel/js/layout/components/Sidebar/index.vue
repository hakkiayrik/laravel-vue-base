<template>
	<v-navigation-drawer app v-model="sidebar.opened">
		<v-list-item>
			<v-list-item-content>
				<v-list-item-title class="title">
					Application
				</v-list-item-title>
				<v-list-item-subtitle>
					subtext
				</v-list-item-subtitle>
			</v-list-item-content>
		</v-list-item>
		
		<v-divider></v-divider>
		
		<v-list dense nav>
			<sidebar-item v-for="route in permission_routes" :key="route.name" :item="route" :base-path="route.path"></sidebar-item>
		</v-list>
	</v-navigation-drawer>
</template>

<script>
import {mapGetters} from 'vuex'
import SidebarItem from './SidebarItem'

export default {
	components: {SidebarItem},
	computed: {
		...mapGetters([
			'permission_routes',
			'sidebar'
		]),
		activeMenu() {
			const route = this.$route
			const {meta, path} = route
			// if set path, the sidebar will highlight the path you set
			if (meta.activeMenu) {
				return meta.activeMenu
			}
			return path
		},
		variables() {
			return variables
		},
		isCollapse() {
			return !this.sidebar.opened
		}
	}
}
</script>
