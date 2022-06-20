import Layout from '../../layout/index.vue'

const routes = {
    path: '/language',
    component: Layout,
    redirect: {name: 'language.index'},
    name: 'language',
    meta: {
        title: 'languages.title',
        icon: 'language',
        crud: true,
        permissions: ['access-language'],
    },
    children: [
        {
            path: 'list',
            name: 'language.index',
            component: () => import('../../views/language/index.vue'),
            hidden: true,
            meta: {
                permissions: ['access-language'],
                icon: 'article',
                title: "languages.index",
                noCache: true
            },
        }
    ]
}

export default routes
