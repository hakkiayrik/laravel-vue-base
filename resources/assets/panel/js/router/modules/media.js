import Layout from '../../layout/index.vue'

const routes = {
	path: '/media',
	component: Layout,
	redirect: {name: 'media.index'},
	name: 'media',
	meta: {
		title: 'media.title',
		icon: 'perm_media',
		crud: true,
		permissions: ['access-media'],
	},
	children: [
		{
			path: 'list',
			name: 'media.index',
			component: () => import('../../views/media/index.vue'),
			hidden: true,
			meta: {
				title: "media.index",
				icon: 'perm_media',
				permissions: ['access-media'],
				noCache: true
			},
		}
	]
}


export default routes