import Layout from '../../layout/index.vue'

const routes = {
	path: '/post',
	component: Layout,
	redirect: {name: 'post.index'},
	name: 'post',
	meta: {
		title: 'posts.title',
		icon: 'article',
		crud: true,
		permissions: ['access-post'],
	},
	children: [
		{
			path: 'list',
			name: 'post.index',
			component: () => import('../../views/post/index.vue'),
			hidden: true,
			meta: {
				permissions: ['access-post'],
				icon: 'article',
				title: "posts.index",
				noCache: true
			},
		},
		{
			path: 'create',
			name: 'post.create',
			component: () => import('../../views/post/create.vue'),
			hidden: true,
			meta: {
				permissions: ['create-post'],
				title: "posts.create",
			},
		},
		{
			path: 'edit/:id',
			name: 'post.edit',
			component: () => import('../../views/post/edit.vue'),
			hidden: true,
			meta: {
				permissions: ['edit-post'],
				title: "posts.edit",
			},
		},
	]
}

export default routes