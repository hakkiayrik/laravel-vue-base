<template>
	<div v-if="!item.hidden">
		<template v-if="hasOneShowingChild(item.children,item) && (!onlyOneChild.children||onlyOneChild.noShowingChildren)&&!item.alwaysShow">
			<v-list-item v-if="onlyOneChild.meta" :to="resolvePath(onlyOneChild.path)">
				<v-list-item-icon v-if="onlyOneChild.meta&&onlyOneChild.meta.icon">
					<v-icon>{{ onlyOneChild.meta.icon }}</v-icon>
				</v-list-item-icon>
				
				<v-list-item-content>
					<v-list-item-title>{{ $t( "menus." + onlyOneChild.meta.title) }}</v-list-item-title>
				</v-list-item-content>
			</v-list-item>
		</template>
		
		<v-list-group v-model="item.active" :prepend-icon="item.meta && item.meta.icon ? item.meta.icon : ''"  no-action v-else >
			<template v-slot:activator>
				<v-list-item-title v-if="item.meta && item.meta.title">
					{{$t("menus." + item.meta.title)}}
				</v-list-item-title>
			</template>
			<v-list-item v-for="child in item.children" v-if="!child.hidden" :key="child.meta.title" :to="resolvePath(child.path)">
				<v-list-item-content>
					<v-list-item-icon v-if="child.meta && child.meta.icon">
						<v-icon>{{child.meta.icon}}</v-icon>
					</v-list-item-icon>
					<v-list-item-title v-if="child.meta && child.meta.title">
						{{$t("menus." + child.meta.title)}}
					</v-list-item-title>
				</v-list-item-content>
			</v-list-item>
		</v-list-group>
	</div>
</template>

<script>
import path from 'path'

export default {
	name: 'SidebarItem',
	props: {
		item: {
			type: Object,
			required: true
		},
		isNest: {
			type: Boolean,
			default: false
		},
		basePath: {
			type: String,
			default: ''
		}
	},
	data() {
		this.onlyOneChild = null
		return {}
	},
	methods: {
		hasOneShowingChild(children = [], parent) {
			const showingChildren = children.filter(item => {
				if (item.hidden) {
					return false
				} else {
					this.onlyOneChild = item
					return true
				}
			})
			
			if (showingChildren.length === 1) {
				return true
			}
			
			if (showingChildren.length === 0) {
				this.onlyOneChild = {...parent, path: '', noShowingChildren: true}
				return true
			}
			
			return false
		},
		resolvePath(routePath) {
			return path.resolve(this.basePath, routePath)
		}
	}
}
</script>
