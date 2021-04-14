import Layout from "../../layout/index.vue";

const routes = {
	path: '/log',
	component: Layout,
	redirect: {name: 'log.index'},
	name: 'log',
	meta: {
		title: 'logs.title',
		icon: 'history',
		crud: true,
		permissions: ['access-log']
	},
	children: [
		{
			path: 'list',
			component: () => import('../../views/log/index.vue'),
			name: 'log.index',
			hidden:true,
			meta: {
				title: 'logs.index',
				permissions: ['access-log']
			}
		}
	]
}

export default routes