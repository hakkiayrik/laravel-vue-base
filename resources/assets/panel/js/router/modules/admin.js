import Layout from '../../layout/index.vue'

const routes = {
	path: '/admin',
	component: Layout,
	redirect: {name: 'admin.index'},
	name: 'admin',
	meta: {
		title: 'admins.title',
		icon: 'manage_accounts',
		crud: true,
		permissions: ['access-admin'],
	},
	children: [
		{
			path: 'list',
			name: 'admin.index',
			component: () => import('../../views/admin/index.vue'),
			hidden: true,
			meta: {
				permissions: ['access-admin'],
				icon: 'people',
				title: "admins.index",
				noCache: true
			},
		},
		{
			path: 'create',
			name: 'admin.create',
			component: () => import('../../views/admin/create.vue'),
			hidden: true,
			meta: {
				permissions: ['create-admin'],
				title: "admins.create",
			},
		},
		{
			path: 'edit/:id',
			name: 'admin.edit',
			component: () => import('../../views/admin/edit.vue'),
			hidden: true,
			meta: {
				permissions: ['edit-admin'],
				title: "admins.edit",
			},
		},
	]
}


export default routes