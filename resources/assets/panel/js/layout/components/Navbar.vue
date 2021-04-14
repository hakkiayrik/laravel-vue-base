<template>
	<v-app-bar app color="primary" elevation="0" dense dark id="headerTopBar">
		<v-app-bar-nav-icon @click="toggleSideBar()"></v-app-bar-nav-icon>
		<breadcrumb id="breadcrumb-container" class="breadcrumb-container" />
		
		<v-spacer></v-spacer>
		<v-badge color="yellow" overlap dot>
			<v-btn color="red" fab x-small  class="ml-3">
				<v-icon color="white" dark>bug_report</v-icon>
			</v-btn>
		</v-badge>
		
		<lang-menu></lang-menu>
		
		<v-menu left bottom rounded="0"  transition="slide-y-transition" offset-y open-on-hover>
			<template v-slot:activator="{ on, attrs }">
				<v-btn icon v-bind="attrs" v-on="on" tile>
					<v-icon>more_vert</v-icon>
				</v-btn>
			</template>
			<v-card>
				<v-list >
					<v-list-item>
						<v-list-item-avatar>
							<img :src="user.avatar.image_path" :alt="user.name" />
						</v-list-item-avatar>
						
						<v-list-item-content>
							<v-list-item-title>{{ user.username }}</v-list-item-title>
							<v-list-item-subtitle>{{ user.name }}</v-list-item-subtitle>
						</v-list-item-content>
						
					</v-list-item>
				</v-list>
				
				<v-divider></v-divider>
				
				<v-list class="pa-0" >
					<v-list-item :to="{name: 'profile'}">
						<v-list-item-icon>
							<v-icon>account_circle</v-icon>
						</v-list-item-icon>
						<v-list-item-title>{{$t('buttons.profile')}}</v-list-item-title>
					</v-list-item>
					<v-list-item @click="logout()">
						<v-list-item-icon>
							<v-icon>power_settings_new</v-icon>
						</v-list-item-icon>
						<v-list-item-title>{{$t('buttons.logout')}}</v-list-item-title>
					</v-list-item>
				</v-list>
			</v-card>
		</v-menu>
	</v-app-bar>
</template>

<script>
import {mapGetters} from 'vuex'
import Breadcrumb from '../../components/Breadcrumb'
import LangMenu from '../../components/LangMenu'

export default {
	components: {
		Breadcrumb,
		LangMenu
	},
	computed: {
		...mapGetters([
			'sidebar',
			'avatar',
			'device',
			'user'
		])
	},
	methods: {
		toggleSideBar() {
			this.$store.dispatch('app/toggleSideBar')
		},
		async logout() {
			await this.$store.dispatch('user/logout')
			this.$router.push(`/login?redirect=${this.$route.fullPath}`)
		},
	}
}
</script>
<style>
.breadcrumb-container {
	float: left;
}
</style>
