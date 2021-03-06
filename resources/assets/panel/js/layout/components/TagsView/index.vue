<template>
	<v-card id="tags-view-container" class="tags-view-container">
		<v-chip-group multiple ref="scrollPane" class="tags-view-wrapper py-1">
			<v-chip
				v-for="tag in visitedViews"
				:key="tag.path"
				:ripple="false"
				link
				ref="tag"
				class="tags-view-item my-0 mr-0 ml-2 rounded-0"
				:class="isActive(tag)?'active':''"
				:color="isActive(tag)?'success': ''"
				@click.middle.native="!isAffix(tag)?closeSelectedTag(tag):''"
				@contextmenu.prevent.native="openMenu(tag,$event)"
				:to="{ path: tag.path, query: tag.query, fullPath: tag.fullPath }"
				label
				flat
			>
				{{ $t("menus." + tag.title) }}
				<v-btn v-if="!isAffix(tag)" @click.prevent.stop="closeSelectedTag(tag)" icon small> <v-icon>close</v-icon> </v-btn>
			</v-chip>
			
		</v-chip-group>
		<ul v-show="visible" :style="{left:left+'px',top:top+'px'}" class="contextmenu">
			<li @click="refreshSelectedTag(selectedTag)">{{ $t('buttons.refresh') }}</li>
			<li v-if="!isAffix(selectedTag)" @click="closeSelectedTag(selectedTag)">{{ $t('buttons.close') }}</li>
			<li @click="closeOthersTags">{{ $t('buttons.close_others') }}</li>
			<li @click="closeAllTags(selectedTag)">{{ $t('buttons.close_all') }}</li>
		</ul>
	</v-card>
</template>

<script>
import path from 'path'

export default {
	data() {
		return {
			visible: false,
			top: 0,
			left: 0,
			selectedTag: {},
			affixTags: []
		}
	},
	computed: {
		visitedViews() {
			return this.$store.state.tagsView.visitedViews
		},
		routes() {
			return this.$store.state.permission.routes
		}
	},
	watch: {
		$route() {
			this.addTags()
			this.moveToCurrentTag()
		},
		visible(value) {
			if (value) {
				document.body.addEventListener('click', this.closeMenu)
			} else {
				document.body.removeEventListener('click', this.closeMenu)
			}
		}
	},
	mounted() {
		this.initTags()
		this.addTags()
	},
	methods: {
		isActive(route) {
			return route.path === this.$route.path
		},
		isAffix(tag) {
			return tag.meta && tag.meta.affix
		},
		filterAffixTags(routes, basePath = '/') {
			let tags = []
			routes.forEach(route => {
				if (route.meta && route.meta.affix) {
					const tagPath = path.resolve(basePath, route.path)
					tags.push({
						fullPath: tagPath,
						path: tagPath,
						name: route.name,
						meta: {...route.meta}
					})
				}
				if (route.children) {
					const tempTags = this.filterAffixTags(route.children, route.path)
					if (tempTags.length >= 1) {
						tags = [...tags, ...tempTags]
					}
				}
			})
			return tags
		},
		initTags() {
			const affixTags = this.affixTags = this.filterAffixTags(this.routes)
			for (const tag of affixTags) {
				// Must have tag name
				if (tag.name) {
					this.$store.dispatch('tagsView/addVisitedView', tag)
				}
			}
		},
		addTags() {
			const {name} = this.$route
			if (name) {
				this.$store.dispatch('tagsView/addView', this.$route)
			}
			return false
		},
		moveToCurrentTag() {
			const tags = this.$refs.tag
			this.$nextTick(() => {
				for (const tag of tags) {
					if (tag.to.path === this.$route.path) {
						// when query is different then update
						if (tag.to.fullPath !== this.$route.fullPath) {
							this.$store.dispatch('tagsView/updateVisitedView', this.$route)
						}
						break
					}
				}
			})
		},
		refreshSelectedTag(view) {
			this.$store.dispatch('tagsView/delCachedView', view).then(() => {
				const {fullPath} = view
				this.$nextTick(() => {
					this.$router.replace({
						path: '/redirect' + fullPath
					})
				})
			})
		},
		closeSelectedTag(view) {
			this.$store.dispatch('tagsView/delView', view).then(({visitedViews}) => {
				if (this.isActive(view)) {
					this.toLastView(visitedViews, view)
				}
			})
		},
		closeOthersTags() {
			this.$router.push(this.selectedTag)
			this.$store.dispatch('tagsView/delOthersViews', this.selectedTag).then(() => {
				this.moveToCurrentTag()
			})
		},
		closeAllTags(view) {
			this.$store.dispatch('tagsView/delAllViews').then(({visitedViews}) => {
				if (this.affixTags.some(tag => tag.path === view.path)) {
					return
				}
				this.toLastView(visitedViews, view)
			})
		},
		toLastView(visitedViews, view) {
			const latestView = visitedViews.slice(-1)[0]
			if (latestView) {
				this.$router.push(latestView.fullPath)
			} else {
				// now the default is to redirect to the home page if there is no tags-view,
				// you can adjust it according to your needs.
				if (view.name === 'Dashboard') {
					// to reload home page
					this.$router.replace({path: '/redirect' + view.fullPath})
				} else {
					this.$router.push('/')
				}
			}
		},
		openMenu(tag, e) {
			const menuMinWidth = 105
			const navbarHeight = document.getElementById('headerTopBar').offsetHeight
			const offsetLeft = this.$el.getBoundingClientRect().left // container margin left
			const offsetWidth = this.$el.offsetWidth // container width
			const maxLeft = offsetWidth - menuMinWidth // left boundary
			const left = e.clientX - offsetLeft + 15 // 15: margin right
			
			if (left > maxLeft) {
				this.left = maxLeft
			} else {
				this.left = left
			}
			
			this.top = e.clientY - navbarHeight
			this.visible = true
			this.selectedTag = tag
		},
		closeMenu() {
			this.visible = false
		},
		handleScroll() {
			this.closeMenu()
		}
	}
}
</script>

<style lang="scss" scoped>
.tags-view-container {
	width: 100%;
	border-bottom: 1px solid #d8dce5;
	box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .12), 0 0 3px 0 rgba(0, 0, 0, .04);
	border-radius: 0!important;
	
	.tags-view-wrapper {
		.tags-view-item {
			display: inline-block;
			position: relative;
			cursor: pointer;
			height: 26px;
			line-height: 26px;
			padding: 0 8px;
			font-size: 12px;
			margin-left: 5px;
			margin-top: 4px;
			
			&:first-of-type {
				margin-left: 15px;
			}
			
			&:last-of-type {
				margin-right: 15px;
			}
			
			&.active {
				background-color: #42b983!important;
				color: #fff!important;
				border-color: #42b983!important;
				
				&::before {
					content: '';
					background: #fff;
					display: inline-block;
					width: 5px;
					height: 5px;
					border-radius: 50%;
					position: relative;
					margin-right: 5px;
					opacity: 1;
				}
			}
		}
	}
	
	.contextmenu {
		margin: 0;
		background: #fff;
		z-index: 3000;
		position: absolute;
		list-style-type: none;
		padding: 5px 0;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 400;
		color: #333;
		box-shadow: 2px 2px 3px 0 rgba(0, 0, 0, .3);
		
		li {
			margin: 0;
			padding: 7px 16px;
			cursor: pointer;
			
			&:hover {
				background: #eee;
			}
		}
	}
}
</style>

<style lang="scss">
//reset element css of el-icon-close
.tags-view-wrapper {
	.tags-view-item {
		
		.v-size--small {
			height: 15px!important;
			width: 15px!important;
			.v-icon  {
				font-size: 9px;
			}
			
		}
		
		&.active {
			.v-size--small {
				.v-icon  {
					color:#fff!important;
				}
				
			}
		}
	}
}
</style>
