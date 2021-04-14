<template>
	<v-main class="app-main p-3">
		<tags-view v-if="needTagsView" />
		<transition name="fade-transform" mode="out-in">
			<keep-alive :include="cachedViews">
				<router-view :key="key"/>
			</keep-alive>
		</transition>
	</v-main>
</template>

<script>
import TagsView from './TagsView'
import { mapState } from 'vuex'

export default {
	name: 'AppMain',
	components: {
		TagsView
	},
	computed: {
		...mapState({
			needTagsView: state => state.settings.tagsView,
		}),
		cachedViews() {
			return this.$store.state.tagsView.cachedViews
		},
		key() {
			return this.$route.path
		}
	}
}
</script>

<style lang="scss" scoped>
.app-main {
	/* 50= navbar  50  */
	min-height: calc(100vh - 50px);
	width: 100%;
	position: relative;
	overflow: hidden;
}

.fixed-header + .app-main {
	padding-top: 50px;
}

.hasTagsView {
	.app-main {
		/* 84 = navbar + tags-view = 50 + 34 */
		min-height: calc(100vh - 84px);
	}
	
	.fixed-header + .app-main {
		padding-top: 84px;
	}
}
</style>

<style lang="scss">
// fix css style bug in open el-dialog
.el-popup-parent--hidden {
	.fixed-header {
		padding-right: 15px;
	}
}
</style>
