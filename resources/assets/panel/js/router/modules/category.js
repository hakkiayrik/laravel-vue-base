import Layout from '../../layout/index.vue'

const routes = {
	path: '/category',
	component: Layout,
	redirect: {name: 'category.index'},
	name: 'category',
	meta: {
		title: 'categories.title',
		icon: 'category',
		crud: true,
		permissions: ['access-category'],
	},
	children: [
		{
			path: 'list',
			name: 'category.index',
			component: () => import('../../views/category/index.vue'),
			hidden: true,
			meta: {
				title: "categories.index",
				icon: 'category',
				permissions: ['access-category'],
				noCache: true
			},
		}
	]
}


export default routes