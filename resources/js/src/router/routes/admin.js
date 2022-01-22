export default [{
        path: '/access-control-admin',
        name: 'access-control-admin',
        component: () =>
            import ('@/views/extensions/acl/AccessControl.vue'),
        meta: {
            resource: 'admin',
            action: 'read',
        },
    },
    {
        path: '/dashboard-admin/ecommerce',
        name: 'dashboard-admin-ecommerce',
        meta: {
            resource: 'admin',
            action: 'read',
        },
        component: () =>
            import ('@/views/dashboard/ecommerce/Ecommerce.vue'),
    },
    {
        path: '/user/list',
        name: 'user-index',
        meta: {
            resource: 'admin',
            action: 'read',
        },
        component: () =>
            import ('@/views/user/users-list/UsersList.vue'),
    },
    {
        path: '/user/show/:id',
        name: 'user-show',
        meta: {
            resource: 'admin',
            action: 'read',
        },
        component: () =>
            import ('@/views/user/users-view/UsersView.vue'),
    },
    {
        path: '/user/edit/:id',
        name: 'user-edit',
        meta: {
            resource: 'admin',
            action: 'read',
        },
        component: () =>
            import ('@/views/user/users-edit/UsersEdit.vue'),
    },

]