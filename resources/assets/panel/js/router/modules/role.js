import Layout from "../../layout/index.vue";

const routes = {
	path: '/role',
	component: Layout,
	redirect: {name: 'role.index'},
	name: 'role',
	meta: {
		title: 'roles.title',
		icon: 'admin_panel_settings',
		crud: true,
		permissions: ['access-role']
	},
	children: [
		{
			path: 'list',
			component: () => import('../../views/role/index.vue'),
			name: 'role.index',
			hidden:true,
			meta: {
				title: 'roles.index',
				permissions: ['access-role']
			}
		},
		{
			path: 'create',
			component: () => import('../../views/role/create.vue'),
			name: 'role.create',
			hidden: true,
			meta: {
				title: 'roles.create',
				permissions: ['create-role']
			}
		},
		{
			path: 'edit/:id',
			component: () => import('../../views/role/edit.vue'),
			name: 'role.edit',
			hidden: true,
			meta: {
				title: 'roles.edit',
				permissions: ['edit-role']
			}
		}
	]
}

export default routes