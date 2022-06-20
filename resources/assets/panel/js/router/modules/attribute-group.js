import Layout from "../../layout/index.vue";

const attributeGroups = {
    path: '/attribute-group',
    component: Layout,
    redirect: {name: 'attribute-group.index'},
    name: 'attribute-group',
    meta: {
        title: 'attribute_groups.title',
        icon: 'ballot',
        crud: true,
        permissions: ['access-attribute']
    },
    children: [
        {
            path: 'list',
            component: () => import('../../views/attribute-group/index.vue'),
            name: 'attribute-group.index',
            hidden:true,
            meta: {
                title: 'attribute-groups.index',
                permissions: ['access-attribute']
            }
        },
        {
            path: 'create',
            component: () => import('../../views/attribute-group/create.vue'),
            name: 'attribute-group.create',
            hidden: true,
            meta: {
                title: 'attribute-groups.create',
                permissions: ['create-attribute']
            }
        },
        {
            path: 'edit/:id',
            component: () => import('../../views/attribute-group/edit.vue'),
            name: 'attribute-group.edit',
            hidden: true,
            meta: {
                title: 'attribute-groups.edit',
                permissions: ['edit-attribute']
            }
        }
    ]
}

export default attributeGroups
