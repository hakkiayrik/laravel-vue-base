import Layout from '../../layout/index.vue'

const routes = {
	path: '/user',
	component: Layout,
	redirect: {name: 'user.index'},
	name: 'user',
	meta: {
		title: 'users.title',
		icon: 'person',
		crud: true,
		permissions: ['access-user'],
	},
	children: [
		{
			path: 'list',
			name: 'user.index',
			component: () => import('../../views/user/index.vue'),
			hidden: true,
			meta: {
				permissions: ['access-user'],
				icon: 'people',
				title: "users.index",
				noCache: true
			},
		},
		{
			path: 'edit/:id',
			name: 'user.edit',
			component: () => import('../../views/user/edit.vue'),
			hidden: true,
			meta: {
				permissions: ['edit-user'],
				title: "users.edit",
			},
		},
	]
}


export default routes