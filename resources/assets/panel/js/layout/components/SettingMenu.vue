<template>
	<v-sheet class="overflow-hidden" style="position: relative;">
		<v-navigation-drawer v-model="settingBar" fixed temporary app right clipped class="settings-menu-wrapper">
			<v-btn color="primary" class="settings-toggle-btn rounded-0" dark @click.stop="settingBar = !settingBar" fab small>
				<v-icon>settings</v-icon>
			</v-btn>
			
			<v-list nav dense>
				<h4 class="pa-2">{{ $t('global.panel_settings') }}</h4>
				<v-list-item dense class="pa-0">
					<v-list-item-title>
						<v-subheader>{{ $t('global.dark_theme') }}</v-subheader>
					</v-list-item-title>
					<v-list-item-action-text>
						<v-switch
							v-model="darkTheme"
							class="ml-3 d-inline-block"
						></v-switch>
					</v-list-item-action-text>
				</v-list-item>
				<v-divider></v-divider>
				<v-list-item dense class="pa-0">
					<v-list-item-title><v-subheader>{{ $t('global.tags_view') }}</v-subheader></v-list-item-title>
					<v-list-item-action-text>
						<v-switch
							v-model="tagsView"
							class="ml-3 d-inline-block"
						></v-switch>
					</v-list-item-action-text>
				</v-list-item>
				<v-divider></v-divider>
				<v-list-item dense class="pa-0">
					<v-list-item-title><v-subheader>{{ $t('global.fixed_app_bar') }}</v-subheader></v-list-item-title>
					<v-list-item-action-text>
						<v-switch
							v-model="fixedHeader"
							class="ml-3 d-inline-block"
						></v-switch>
					</v-list-item-action-text>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>
	</v-sheet>
</template>
<script>
import Cookies from 'js-cookie'

export default {
	computed: {
		darkTheme: {
			get() {
				return this.$store.state.settings.darkTheme
			},
			set(val) {
				this.$vuetify.theme.dark = !this.$vuetify.theme.dark
				Cookies.set('darkTheme', this.$vuetify.theme.dark ? 1 : 0)
				this.$store.dispatch('settings/changeSetting', {
					key: 'darkTheme',
					value: this.$vuetify.theme.dark
				})
			}
		},
		tagsView: {
			get() {
				return this.$store.state.settings.tagsView
			},
			set(val) {
				this.$store.dispatch('settings/changeSetting', {
					key: 'tagsView',
					value: val
				})
			}
		},
		fixedHeader: {
			get() {
				return this.$store.state.settings.fixedHeader
			},
			set(val) {
				this.$store.dispatch('settings/changeSetting', {
					key: 'fixedHeader',
					value: val
				})
			}
		}
	},
	data() {
		return {
			settingBar: null
		}
	},
	name: "SettingMenu",
	methods: {
		setSettings(name) {
			this.$store.dispatch('setting/')
		}
	}
}
</script>

<style scoped>
	.settings-menu-wrapper {
		visibility: visible!important;
		overflow: initial!important;
	}
	.settings-toggle-btn {
		position: fixed;
		bottom:0;
		left:-40px;
	}
</style>